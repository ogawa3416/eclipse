package sample20_03;

public class Number {
	private	int val = 10;
	static int num = 20;

	public void dispVal() {
		System.out.println("val=" + val);
		System.out.println("num=" + num);
	}
	public static void main(String[] args){
		//System.out.println(this.val);
		//System.out.println(this.num);
	}
	public int getVal() {
		return val;
	}
	public void setVal(int val) {
		this.val = val;
	}	
}
