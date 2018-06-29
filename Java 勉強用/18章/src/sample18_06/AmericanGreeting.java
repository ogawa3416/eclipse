package sample18_06;

public class AmericanGreeting extends Greeting {
	@Override
	public String language(){
		return	"American";
	}
	@Override
	public String message(){
		return	"Hello!";
	}	
}
