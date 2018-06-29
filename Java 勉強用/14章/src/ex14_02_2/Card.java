	package ex14_02_2;
	public	class Card {
		String	suit;		// カードの種類 "スペード"、"ハート"、"クラブ"、"ダイヤ"
		int		number;		// カードの札番号  1～13
		public	Card(String suit, int number){

		}
		public	Card(String suit){		// numberは常に1とする
			
		}
		public	Card(int number ){		// suitは常に”スペード”とする
			
		}

		String	face(){					// カードを表す文字列を返す
			return	suit+"/"+number; 
		}
	}
