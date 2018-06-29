package sample20_02;
public class Number {
	private int val = 10;
	static int num = 20;
	public void dispVal() {
		System.out.println("val=" + val);
	}
	public static void dispNum() {
		//System.out.println("val=" + val);	// コンパイルエラー
		System.out.println("num=" + num);
	}
	public Number(int val) {
		super();
		this.val = val;
	}
	public int getVal() {
		return val;
	}
	public static int getNum() {
		return num;
	}
}
