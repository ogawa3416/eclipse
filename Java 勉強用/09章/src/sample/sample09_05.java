package sample;

import lib.Input;

public class sample09_05 {

	public static void main(String[] args) {
		int cmd;
		do {
			System.out.println("--- 何かの処理 ---");
			cmd = Input.getInt("repeat=1/stop=0");
		}while(cmd==1);

	}

}
