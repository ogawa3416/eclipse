package sample16_03;

import sample.Dice;

public class Exec {
	public static void main(String[] args) {
		Dice dice = new Dice();
		dice.play();
		System.out.println("目数＝" + dice.getVal());
	}
}
