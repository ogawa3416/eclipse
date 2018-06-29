package sample19_03;

public class Exec {
	public static void main(String[] args) {
		Responsible res = new Member(118, "田中宏");
		System.out.println(res.info());
		System.out.println(res.exp());
		//System.out.println(res.getName());
	}
}
