package com.marineroad.springboot;

import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMapping;


@Controller
public class ThymeleafController {

	@RequestMapping("/index")
	public String index() {
		return "index";
	}
	
	@RequestMapping("/about")
    public String about() {
        return "about";
    }
	
	@RequestMapping("/services")
    public String service() {
        return "services";
    }
	
	@RequestMapping("/contact")
    public String contact() {
        return "contact";
    }

}