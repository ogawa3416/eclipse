package ex14_03_1;
public class Grade{
	String	name; 
	int		score; 
	public	Grade(String	name, int score){
		this.name  	=	name;
		this.score	=	score;
	}
	public	String judge(){
		String			str	=	"合格";
		if(score<70)	str	=	"不合格";
		return	str;
	}
}

