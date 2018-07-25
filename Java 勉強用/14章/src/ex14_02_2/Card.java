	package ex14_02_2;
	public	class Card {
		String	suit;		// カードの種類 "スペード"、"ハート"、"クラブ"、"ダイヤ"
		int		number;		// カードの札番号  1～13
		public	Card(String suit, int number){
			this.suit = suit;
			this.number = number;
		}
		public	Card(String suit){		// numberは常に1とする
			this(suit,1);
		}
		public	Card(int number ){		// suitは常に”スペード”とする
			this("スペード",number);
		}
		public Card() {
			
		}

		String	face(){					// カードを表す文字列を返す
			return	suit+"/"+number; 
		}
	}
