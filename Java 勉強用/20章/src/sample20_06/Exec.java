package sample20_06;
class Person {
	String name = "田中宏"; // テストのためデフォルトアクセスにしてある
}
class Friend extends Person {
	String name = "ひろちゃん"; // テストのためデフォルトアクセスにしてある
	public void printSuperName(){
		System.out.println(super.name);
	}
}
public class Exec {
	public static void main(String[] args) {
		Friend friend = new Friend(); 			// スーパークラス型の変数に入れる
		friend.printSuperName();
	}
}
