package sample20_04;
class Person {
	String name = "田中宏"; // テストのためデフォルトアクセスにしてある
}
class Friend extends Person {
	String name = "ひろちゃん"; // テストのためデフォルトアクセスにしてある
}

public class Exec {
	public static void main(String[] args) {
		Friend friend = new Friend(); 			// スーパークラス型の変数に入れる
		Person person = friend; 				// スーパークラス型の変数に入れる
		System.out.println(friend.name); 		// name変数をそのまま出力する
		System.out.println(person.name); 		// name変数をそのまま出力する
	}
}
