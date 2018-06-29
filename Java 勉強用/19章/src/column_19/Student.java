package column_19;

public class Student  implements Comparable<Student>{
	String		name;
	private	int	id;
	public	Student(String name, int id){
		this.name = name;
		this.id	=	id;
	}
	@Override
	public int compareTo(Student obj) {
		return id - obj.id;
	}
	public String toString(){
		return	id + ":" + name;
	}
}
