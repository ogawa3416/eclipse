package Ex15_04_1;

public class Exec {

	public static void main(String[] args) {
		Card[] cards = {new Card(0,1), new Card(1,1), new Card(2,1), new Card(3,1)};
		for(Card card : cards) {
			System.out.println(card.toString() + "\t");
		}

	}

}
