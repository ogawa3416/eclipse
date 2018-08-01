package ex18_02_2;

public class DoubleAdder extends Adder {

	public DoubleAdder(int val) {
		super(val);
	}
	@Override
	public void add(int num) {
		int temp = getVal();
		temp += num*2;
		setVal(temp);
	}

}
