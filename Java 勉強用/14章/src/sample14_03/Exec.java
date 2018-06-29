package sample14_03;

public class Exec {
	public static void main(String[] args) {
		Dice dice1 = new Dice(6);
		Dice dice2 = new Dice();
		System.out.println("dice1＝" + dice1.val);
		System.out.println("dice2＝" + dice2.val);
	}
}
