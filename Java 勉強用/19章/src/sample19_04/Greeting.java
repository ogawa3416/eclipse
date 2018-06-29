package sample19_04;

public class Greeting implements Responsible {
	public String language(){
		return	null;
	}
	public	String message(){
		return null;
	}
	@Override
	public String info() {
		return "Greetingクラス ver 1.0";
	}
	@Override
	public String exp() {
		return "挨拶のクラス";
	}
}


