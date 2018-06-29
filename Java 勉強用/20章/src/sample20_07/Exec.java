package sample20_07;
interface Printable {
	String getString(); // 文字列を返す
	default void print() { // 文字列を出力する
		System.out.println(getString()); // getStringの戻り値を出力する
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
		Person tanaka = new Person("田中宏"); // Person型のオブジェクトを作成
		tanaka.print(); // 文字列を出力
	}
}
