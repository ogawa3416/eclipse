package ex14_03_1;
public class Grade{
	private String	name; 
	private int		score; 
	public	Grade(String	name, int score){
		this.name  	=	name;
		this.score	=	score;
	}
	public	String judge(){
		String			str	=	"合格";
		if(score<70)	str	=	"不合格";
		return	str;
	}
	
	public String getName() {
		return name;
	}
	public void setName(String name) {
		this.name = name;
	}
	public int getScore() {
		return score;
	}
	public void setScore(int score) {
		this.score = score;
	}
	
}

