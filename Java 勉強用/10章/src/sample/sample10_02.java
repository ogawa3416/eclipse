package sample;

import lib.Input;

public class sample10_02 {

	public static void main(String[] args) {
		double val = Input.getDouble();
		if(val< 0) {
			val = -1 * val;
		}
		System.out.println(val + "の平方根=" + Math.sqrt(val));

	}

}
