package com.marineroad.springboot;

import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
 
 
@Controller
public class IndexController {
  @RequestMapping(value="/",method=RequestMethod.GET)
  String index(){
    return "index";
  }
  @RequestMapping(value="/index2",method=RequestMethod.GET)
  String index2(){
    return "index2";
  }
}