package sample;

import lib.Input;

public class sample10_01 {

	public static void main(String[] args) {
		int num = Input.getInt("投票");
		
		if(num==1) {
			System.out.println("賛成");
		}else {
			System.out.println("反対");
		}

	}

}
