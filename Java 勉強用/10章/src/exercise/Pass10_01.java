package exercise;

import lib.Input;

public class Pass10_01 {

	public static void main(String[] args) {
		String str;
		
		while((str = Input.getString()) != null) {
			if(str.equals("dog")) {
				System.out.println("いぬ");
			}else if(str.equals("cat")){
				System.out.println("ねこ");
				
			}else if(str.equals("mouse")) {
				System.out.println("ねずみ");
			}else if(str.equals("rabbit")) {
				System.out.println("うさぎ");
			}else {
				System.out.println("?");
			}
		}

	}

}
