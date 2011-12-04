cd build/classes
del /Q /S *
javac -d . -Xlint:unchecked -classpath "..\..\lib\*" ..\..\src\aquadew\core\*.java ..\..\src\aquadew\interfaces\*.java ..\..\src\aquadew\util\*.java ..\..\src\aquadew\remote\*.java
jar cvfm ..\..\dist\aquadew.jar ..\MANIFEST .\*
pause
