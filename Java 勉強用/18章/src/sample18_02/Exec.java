package sample18_02;
public class Exec {
	public static void main(String[] args) {
		Object obj = new Member(118, "田中宏");
		System.out.println(obj instanceof Object);
		System.out.println(obj instanceof Member);
		System.out.println(obj instanceof Student);
	}
}
