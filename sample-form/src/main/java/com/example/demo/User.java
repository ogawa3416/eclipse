package com.example.demo;

public class User {
	public String name;
	public String mail;
	public String age;

	public User(String name, String mail, String age) {
		this.name = name;
		this.mail = mail;
		this.age = age;
	}

	public String getName() {
		return name;
	}

	public String getMail() {
		return mail;
	}

	public String getAge() {
		return age;
	}
}