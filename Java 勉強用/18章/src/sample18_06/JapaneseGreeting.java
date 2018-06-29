package sample18_06;

public class JapaneseGreeting  extends Greeting {
	@Override
	public String language(){
		return	"Japanese";
	}
	@Override
	public String message(){
		return	"こんにちは!";
	}
}
