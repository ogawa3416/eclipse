package sample18_06;
public class Talker {
	public void run(Greeting greeting){
		System.out.println(greeting.language());
		System.out.println(greeting.message());
	}
}
