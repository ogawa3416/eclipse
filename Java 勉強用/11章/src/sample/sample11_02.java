package sample;

import lib.Input;

public class sample11_02 {

	public static void main(String[] args) {
		int value = Input.getInt();
		switch (value) {
		case 7:
			System.out.println("ラベル7の処理");
		case 10:
			System.out.println("ラベル10の処理");
		default:
			System.out.println("ラベルdefaultの処理");
		}
		
		System.out.println("終了");

	}

}
