package exercise;

import lib.Input;

public class Pass09_03 {

	public static void main(String[] args) {
		int n=0;
		
		do {
			System.out.println(Math.random());
			n = Input.getInt();
		}while(n !=0);
		

	}

}
