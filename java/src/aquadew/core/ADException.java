package aquadew.core;

/**
 *	@interface ADException
 *	@desc Aquadew system exception interface
 *
 *	@reference http://www.json.org/javadoc/org/json/JSONException.html
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
 **/
public class ADException extends SBException {
	
	private static final long serialVersionUID = 0;
	private Throwable cause;

    /**
	 *	@method constructor
	 *	@desc Constructs a ADException with an explanatory message
	 *
	 *	@param message - Detail about the reason for the exception
	 *
	**/
    public ADException(String message) {
        super(message);
    }

    public ADException(Throwable cause) {
        super(cause.getMessage());
        this.cause = cause;
    }

    public Throwable getCause() {
        return this.cause;
    }
	
}

