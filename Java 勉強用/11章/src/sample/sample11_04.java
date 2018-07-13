package sample;

import lib.Input;

public class sample11_04 {

	public static void main(String[] args) {
		String str = Input.getString();
		switch (str) {
		case "おひつじ座":
		case "おうし座":
		case "ふたご座":
			System.out.println("春から夏(3/21～6/21)");
			break;
		case "かに座":
		case "しし座":
		case "おとめ座":
			System.out.println("春から秋(6/22～9/22)");
			break;
		case "てんびん座":
		case "さそり座":
		case "いて座":
			System.out.println("秋から冬(9/23～12/21)");
			break;
		default:
			System.out.println("冬から春(12/22～3/20)");
		}

	}

}
