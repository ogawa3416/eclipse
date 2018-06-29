package exercise;
import lib.Input;
public class Pass05_04 {

	public static void main(String[] args) {
		
		String str1 = Input.getString();
		
		int n = str1.length();
		String str2 = str1.toUpperCase();
		String str3 = str1.substring(0, 5);
		
		System.out.println("str1=" + str1);
		System.out.println("n =" + n);
		System.out.println("str2=" + str2);
		System.out.println("str3=" + str3);
		
	}

}
