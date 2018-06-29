package sample20_05;
class Person {
	static String name = "田中宏"; 
}
class Friend extends Person {
	static String name = "ひろちゃん";
}
public class Exec {
	public static void main(String[] args) {
		System.out.println(Friend.name); 
		System.out.println(Person.name); 
	}
}
