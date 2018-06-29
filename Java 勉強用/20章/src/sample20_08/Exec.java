package sample20_08;
interface Printable {
	String getString(); // 文字列を返す
	static void print() { // メッセージを出力する
		System.out.println("**Printableインタフェース**");
	}
}
class Person implements Printable { // 「人」を表すクラス
	private String name; // 名前
	public Person(String name) { // コンストラクタ
		this.name = name;
	}
	@Override
	public String getString() { // インタフェースの抽象メソッドをオーバーライド
		return name; // 文字列として氏名を返す
	}
}
public class Exec {
	public static void main(String[] args) {
		Person tanaka = new Person("田中宏");
		Printable.print(); // インタフェース名.メソッド名 で使う
	}
}
