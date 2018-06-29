package ex18_02_2;
public class Adder {
	private	int	val;
	public Adder(int val){
		this.val = val;
	}
	public void add(int num){
		val += num;
	}
	public int getVal() {
		return val;
	}
	public void setVal(int val) {
		this.val = val;
	}
}
