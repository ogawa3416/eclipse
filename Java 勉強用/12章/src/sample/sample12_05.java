package sample;

import lib.Input;

public class sample12_05 {

	public static void main(String[] args) {
		dispTitle();
		String name = Input.getString("名前");
		int score = Input.getInt("点数");
		String grade = toGrade(score);
		dispResult(name, score, grade);

	}
	// タイトルの表示
	public static void dispTitle() {
		System.out.println("■■ 点数から評価の変換 ■■");
	}
	// 点数から評価を得る
	public static String toGrade(int score) {
		String grade;
		if(score>=90) {
			grade = "AA";
		}else if(score>= 80) {
			grade = "A";
		}else if(score>= 70) {
			grade = "B";
		}else if(score>= 60) {
			grade = "C";
		}else{
			grade = "D";
		}
		return grade;
	}
	// 結果を表示する
	public static void dispResult(String name, int score, String grade) {
		System.out.println(name + "さんの成績は" + grade + "("+ score + "点)です" );
	}

}
