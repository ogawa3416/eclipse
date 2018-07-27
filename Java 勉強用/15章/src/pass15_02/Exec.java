package pass15_02;

import lib.Input;

public class Exec {

	public static void main(String[] args) {
		Card[] cards = new Card[5];
		for(int i=0; i<cards.length; i++) {
			int suit = Input.getInt("種 類 (0～3)");
			int number = Input.getInt("札番号(1～13)");
			cards[i] = new Card(suit, number);
		}
		for(Card card : cards) {
			System.out.println(card.getSuitString() + card.getNumber());
		}

	}

}
