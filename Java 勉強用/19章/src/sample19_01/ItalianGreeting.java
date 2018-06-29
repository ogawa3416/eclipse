package sample19_01;

public class ItalianGreeting extends Greeting {
	@Override
	public String language(){
		return	"Italian";
	}
	@Override
	public String message(){
		return	"ciao";
	}
}
