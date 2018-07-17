package exercise;

import lib.Input;

public class Pass12_02 {

	public static void main(String[] args) {
		String str = Input.getString("テストする文字列");
		String msg = isEmpty(str) ? "nullまたは空" : "nullでも空でもない";
        System.out.println(msg);
    }
    public static boolean isEmpty(String str){
        return  str==null || str.length()==0;
    }
}