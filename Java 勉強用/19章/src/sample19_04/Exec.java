package sample19_04;

public class Exec {
	public static void main(String[] args) {
		Member member		=	new Member(118, "田中宏");
		Member student		=	new Student(120, "佐藤修", "A223");
		Greeting greeting 	=	new Greeting();
		Information.print(member);
		Information.print(student);
		Information.print(greeting);
	}
}
