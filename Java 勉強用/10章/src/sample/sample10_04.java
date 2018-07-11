package sample;

import lib.Input;

public class sample10_04 {

	public static void main(String[] args) {
		int score = Input.getInt("得点");
		String grade;
		
		if(score>=90) {
			grade = "AA";
		}else if(score >= 80) {
			grade = "A";
		}else if(score >= 70) {
			grade = "B";
		}else if(score >= 60) {
			grade = "C";
		}else {
			grade = "D";
		}
		
		System.out.println("成績は"+ grade + "です");

}
}