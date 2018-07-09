package sample;

import lib.Input;

public class sample08_06 {

	public static void main(String[] args) {
		int a = Input.getInt();
		int n = a%2 == 0 ? 100 : 0;
		System.out.println("n=" + n);

	}

}
