/**
 *	@program evaluator
 *	@desc evaluates the performance of any program
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *	@acknowledgments 
 *		1. Chennai Mathematical Institute for Online Programming Contest Judge <runner.c>
 *		2. Ian Wienand <ianw@ieee.org>  for <http://www.wienand.org/junkcode/linux/stopper.c>
 *
 *	@param i string input file
 *	@param o string output file
 *	@param d string change directory
 *	@param m integer memory limit (in bytes)
 *	@param s integer stack limit (in bytes)
 *	@param f integer output limit (in bytes)
 *	@param l integer file limit (count)
 *	@param t integer time limit (in seconds)
 *	@param x integer maximum time limit (in seconds)
 *
 *	@usage evaluator i:path/to/file o:path/to/file m:67108864 t:2 x:5
 *
 *	@return valid boolean error-less execution
 *	@return msg string message
 *	@return status integer status code
 *	@return details string execution details
 *	@return cdstatus boolean chdir success status
 *	@return euid integer effective user id
 *	@return egid integer effective group id
 *	@return selfroot boolean executed as root
 *	@return status integer status
 *	@return signal integer signal
 *	@return exit_status integer exit status
 *	@return totaltime double total execution time
 *	@return usertime double user time
 *	@return systime double system time
 *	@return memory long memory
 *	@return mjpf long major page faults
 *	@return mnpf long minor page faults
 *	@return vcsw long voluntary context switches
 *	@return ivcsw long involuntary context switches
 *	@return fsin long file system inputs
 *	@return fsout long file system outputs
 *	@return msgrcv long messages received
 *	@return msgsnd long messages sent
 *	@return signals long signals
 *
**/
#include <math.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>
#include <unistd.h>

#include <sys/resource.h>
#include <sys/time.h>
#include <sys/ptrace.h>
#include <signal.h>
#include <sys/types.h>
#include <sys/wait.h>

/**
 *	@constants options set using PTRACE_SETOPTIONS
**/
#define PTRACE_O_TRACESYSGOOD   		0x00000001
#define PTRACE_O_TRACEFORK      			0x00000002
#define PTRACE_O_TRACEVFORK     		0x00000004
#define PTRACE_O_TRACECLONE     		0x00000008
#define PTRACE_O_TRACEEXEC      			0x00000010
#define PTRACE_O_TRACEVFORKDONE 	0x00000020
#define PTRACE_O_TRACEEXIT      			0x00000040
#define PTRACE_O_MASK           				0x0000007f

/**
 *	@constants wait extended result codes for the above trace options  
**/
#define PTRACE_EVENT_FORK       		1
#define PTRACE_EVENT_VFORK      		2
#define PTRACE_EVENT_CLONE      		3
#define PTRACE_EVENT_EXEC       		4
#define PTRACE_EVENT_VFORK_DONE 	5
#define PTRACE_EVENT_EXIT       		6

enum { true, false } bool;

char *input=NULL, *output=NULL, *cdpath=NULL;
int lmt_memory=64*1024*1024, lmt_stack=8*1024*1024, lmt_fsize=50*1024*1024, lmt_time=2;
int lmt_file=16, lmt_nproc=1, lmt_time_max=0;

int cdstatus=1, euid=0, egid=0, selfroot=1, status=0, sig=0, exit_status=0;
double usertime=0.0, systime=0.0, totaltime=0.0;
long memory=0, major_page_faults=0, minor_page_faults=0, voluntary_context_switches=0, involuntary_context_switches=0, file_system_inputs=0, file_system_outputs=0, socket_messages_received=0, socket_messages_sent=0, signals=0;

void fail(char *msg, char *details){
	printf("{\"valid\":\"false\", \"status\":500, \"msg\":\"%s\", \"details\":\"%s\"}\n", msg, details);
	exit(1);
}

void success(char *msg, char *details){
	printf("{\"valid\":\"true\", \"status\":200, \"msg\":\"%s\", \"details\":\"%s\", \"cdstatus\":\"%s\", \"euid\":%d, \"egid\":%d, \"selfroot\":\"%s\", \"status\":%d, \"signal\":%d, \"exit_status\":%d, \"totaltime\":%f, \"usertime\":%f, \"systime\":%f, \"memory\":\%ld, \"mjpf\":%ld, \"mnpf\":%ld, \"vcsw\":%ld, \"ivcsw\":%ld, \"fsin\":%ld, \"fsout\":%ld, \"msgrcv\":%ld, \"msgsnd\":%ld, \"signals\":%ld}\n", msg, details, (cdstatus ? "true" : "false"), euid, egid, (selfroot ? "true" : "false"), status, sig, exit_status, totaltime, usertime, systime ,memory, major_page_faults, minor_page_faults, voluntary_context_switches, involuntary_context_switches, file_system_inputs, file_system_outputs, socket_messages_received, socket_messages_sent, signals);
	exit(0);
}

int parse_cmd_line(int argc, char *argv[]){
	int i;
	
	for(i=1; argv[i][1] == ':'; i++){
		switch(argv[i][0]){
			case 'i' :
				input = &argv[i][2];
				break;
			case 'o' :
				output = &argv[i][2];
				break;
			case 'd' :
				cdpath = &argv[i][2];
			case 'm' :
				sscanf(argv[i], "m:%d", &lmt_memory);
				break;
			case 's' :
				sscanf(argv[i], "s:%d", &lmt_stack);
				break;
			case 'f' :
				sscanf(argv[i], "f:%d", &lmt_fsize);
				break;
			case 'l' :
				sscanf(argv[i], "l:%d", &lmt_file);
				break;
			case 't' :
				sscanf(argv[i], "t:%d", &lmt_time);
				break;
			case 'x' :
				sscanf(argv[i], "x:%d", &lmt_time_max);
				break;
			default :
				break;
		}
	}
	
	return i;
}

int execute_cmd(int argc, char *argv[]){
	struct rlimit rl;
	char **commands;
	int i;
	
	commands = (char**)malloc(sizeof(char*)*(argc + 1));
	for (i = 0; i < argc; i++)
		commands[i] = argv[i];
		
	rl.rlim_cur = (int)(lmt_time + 1); 
	rl.rlim_max = lmt_time_max;
	if (setrlimit(RLIMIT_CPU,&rl))
		fail("Error setting Time limit", "Error @setrlimit/RLIMIT_CPU");
	
	rl.rlim_cur = rl.rlim_max = lmt_memory;
	if (setrlimit(RLIMIT_DATA ,&rl)) 
		fail("Error setting Memory limit", "Error @setrlimit/RLIMIT_DATA");
		
	rl.rlim_cur = rl.rlim_max = lmt_memory; 
	if (setrlimit(RLIMIT_AS,&rl)) 
		fail("Error setting Memory limit", "Error @setrlimit/RLIMIT_AS");
	
	rl.rlim_cur = rl.rlim_max = lmt_fsize; 
	if (setrlimit(RLIMIT_FSIZE,&rl)) 
		fail("Error setting Output limit", "Error @setrlimit/RLIMIT_FSIZE");
	
	rl.rlim_cur = rl.rlim_max = lmt_file; 
	if (setrlimit(RLIMIT_NOFILE,&rl)) 
		fail("Error setting File limit", "Error @setrlimit/RLIMIT_NOFILE");
	
	rl.rlim_cur = lmt_stack; 
	rl.rlim_max = lmt_stack + 4096; 
	if (setrlimit(RLIMIT_STACK,&rl)) 
		fail("Error setting Stack limit", "Error @setrlimit/RLIMIT_STACK");
	
	if(input && freopen(input, "r", stdin)==NULL)
		fail("Error opening input file", "Error @freopen/STDIN");
	
	if(output && freopen(output, "w", stdout)==NULL)
		fail("Error opening output file", "Error @freopen/STDOUT");
	
	if(cdpath && chdir(cdpath) && chroot(cdpath))
		cdstatus = 0;
	
	euid = geteuid();
	egid = getegid();
	
	if(setresgid(65534,65534,65534) || setresuid(65534,65534,65534))
		selfroot = 0;
	
	rl.rlim_cur = rl.rlim_max = lmt_nproc;  
	if (setrlimit(RLIMIT_NPROC,&rl)) 
		fail("Error setting Process limit", "Error @setrlimit/RLIMIT_NPROC");
	
	if (!geteuid() || !getegid())
		fail("Invalid to run as root", "Running as root is disallowed");
	
	execve(commands[0], commands, NULL);
	
	fail("Unable to Execute Command", "Error executing program");
	return 1;
}

int main(int argc, char *argv[]){
	int cmd_index, i;
	pid_t pid, watcher;
	struct rusage usage;
	struct timeval begin, end;
	char ps[32], buf[32];
	FILE *fp;
	
	cmd_index = parse_cmd_line(argc, argv);
	
	if (lmt_time_max < 1 + (int)(lmt_time + 1))
		lmt_time_max = 1 + (int)(lmt_time + 1);
	
	for (i = 3; i < (1<<16); i++)
		close(i);
	
	gettimeofday(&begin, NULL);
	pid = fork();
	
	if (pid == 0) {
		if(ptrace(PTRACE_TRACEME, 0, 0, 0) < 0){
			fail("Tracing Failed", "PTRACE_TRACEME failure");
		}
		else
			return execute_cmd(argc - cmd_index, argv + cmd_index);
	}
	
	wait(NULL);
	if (ptrace(PTRACE_SETOPTIONS, pid, 0, PTRACE_O_TRACEEXIT))
		fail("Tracing Failed", "PTRACE_SETOPTIONS failure");
	
	if (ptrace(PTRACE_CONT, pid, 0, (void*)0))
		fail("Tracing Failed", "PTRACE_CONT failure");
		
	watcher = fork ();
	if (watcher == 0){
		sleep(4*lmt_time_max);
		kill (pid, 9);
		fail("Program Hanged", "Recoverd from hang using watcher");
	}
	
	while(1){
		waitpid(pid, &status, 0);
		if((WSTOPSIG(status) == SIGTRAP) && (status & (PTRACE_EVENT_EXIT << 8)))
			break;
			
		if (ptrace(PTRACE_CONT, pid, 0, WSTOPSIG(status)))
			fail("Tracing Failed", "WSTOPSIG failure");
	}
	
	sprintf(ps, "/proc/%d/statm", pid);
	fp = fopen(ps, "r");
	if(fp){
		fread(buf, 1, 32, fp);
		sscanf(buf, "%d", &memory);
		//printf("%s %d BUF[%s]", ps, fp, buf);
		//fflush(stdout);
		fclose(fp);
	}
	
	if(ptrace(PTRACE_CONT, pid, 0, 0))
		fail("Tracing Failed", "PTRACE_CONT failure");
	
	wait4(pid,&status, 0, &usage);
	gettimeofday(&end, NULL);
	kill(watcher, 9);
	waitpid(watcher, NULL, 0);

	//printf("%f,%f %s %d BUF[%s]", (double)(begin.tv_sec*1000000 + begin.tv_usec), (double)(end.tv_sec*1000000 + end.tv_usec), ps, fp, buf);fflush(stdout);
	//fclose(fp);
	//while(ch = fgetc(fp)) putchar(ch);
	
	totaltime = (double)(end.tv_sec*1000000 + end.tv_usec - begin.tv_sec*1000000 + begin.tv_usec)/1000000;
	usertime = (double) (usage.ru_utime.tv_sec) + ((double) usage.ru_utime.tv_usec)/1000000;
	systime = (double) (usage.ru_stime.tv_sec) + ((double)usage.ru_stime.tv_usec)/1000000;
	memory *= getpagesize();
	major_page_faults = usage.ru_majflt;
	minor_page_faults = usage.ru_minflt;
	voluntary_context_switches = usage.ru_nvcsw;
	involuntary_context_switches = usage.ru_nivcsw;
	file_system_inputs = usage.ru_inblock;
	file_system_outputs = usage.ru_oublock;
	socket_messages_received = usage.ru_msgrcv;
	socket_messages_sent = usage.ru_msgsnd;
	signals = usage.ru_nsignals;
	
	if(WIFSIGNALED(status)){
		sig = WTERMSIG(status);
		
		switch(sig){
			case SIGXCPU :
				fail("Time Limit Exceeded", "SIGXCPU TLE");
				break;
			case SIGFPE :
				fail("Floating Point Exception", "SIGFPE FPE");
				break;
			case SIGILL :
				fail("Illegal Instruction", "SIGILL ILL");
				break;
			case SIGSEGV :
				fail("Segmentation Fault", "SIGSEGV SEG");
				break;
			case SIGABRT :
				fail("Aborted", "SIGABRT ABRT");
				break;
			case SIGBUS :
				fail("Bus Error Bad Memory Access", "SIGBUS BUS");
				break;
			case SIGSYS :
				fail("Invalid System Call", "SIGSYS SYS");
				break;
			case SIGXFSZ :
				fail("Output File Too Large", "SIGXFSZ XFSZ");
				break;
			case SIGKILL :
				fail("Program Killed", "SIGKILL KILL");
				break;
			default :
				fail("Unknown Error", "DEFAULT UNK");
				break;
				break;
		}
	}
	
	if (usertime + systime > lmt_time) 
		fail("Time Limit Exceeded", "TLE");
	
	if (!WIFEXITED(status))
		fail("Program Exited Abnormally", "WIFEXITED");
	
	if (WEXITSTATUS(status))
		fail("Program Returned Non-zero Return Value", "WEXITSTATUS");
	
	success("Successfully Executed", "Program ran successfully");
	
	return 0;
}
