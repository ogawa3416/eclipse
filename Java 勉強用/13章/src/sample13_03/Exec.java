package sample13_03;

public class Exec {

	public static void main(String[] args) {
		Dice dice = new Dice();
		dice.val = 1;
		System.out.println("目数＝" + dice.val);
		dice.play();
		System.out.println("目数＝" + dice.val);
	}
}
