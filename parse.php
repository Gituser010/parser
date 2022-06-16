<?php
//fumctiom write on stdout instruction element in XML format
function write_instruction($string){
    static $order=0;
    $order++;
    switch($string)
    {
        case "CREATEFRAME":
        case "PUSHFRAME":
        case "POPFRAME":
        case "RETURN":
        case "BREAK":
            print("\t<instruction order=\"$order\" opcode=\"$string\"/>\n");
            break;
        default:
            print("\t<instruction order=\"$order\" opcode=\"$string\">\n");
            break;

    }
    return;
}
//function check and write on stdout arg with type var in XML format
function check_var($string,$arg_number){
    $string=explode('@',trim($string,"\n"));
    if($string[0]=='LF' || $string[0]=='GF' || $string[0]=='TF')
    {
        if(sizeof($string)==2)
        {
            $char=$string[1][0];
            if((strtoupper($char)<='Z' && strtoupper($char)>='A')|| $char=='_' || $char=='-' || $char=='$' || $char=='&' || $char=='%' || $char=='*' || $char =='!' || $char=='?') 
            {
                if(str_contains($string[1],'&'))
                {
                    $string[1]=str_replace("&","&amp;",$string[1]);
                }
                if(str_contains($string[1],'<'))
                {
                    $string[1]=str_replace("<","&lt;",$string[1]);
                }
                if(str_contains($string[1],'>'))
                {
                    $string[1]=str_replace(">","&gt;",$string[1]);
                }
                print("\t\t<arg$arg_number type=\"var\">$string[0]@$string[1]</arg$arg_number>\n");
            }
            else
            {
                exit(23);
            }
        return;
        }
        else
        {
            exit(23);
        }
    }
    else
    {
        exit(23);
    }
}
//function check and write on stdout arg with type type in XML format
function check_type($string,$arg_number){
    if($string=="int" || $string=="bool" || $string=="nil" || $string=="string")
    {
        print("\t\t<arg$arg_number type=\"type\">$string</arg$arg_number>\n");
    }
    else
    {
        exit(23);
    }
}
//function check and write on stdout arg with type {bool, int, nil, string} in XML format
function check_symb(string $string,$arg_number){
    $string=explode('@',trim($string,"\n"));
    if($string[0]=="int" || $string[0]=="bool" || $string[0]=="nil" || $string[0]=="string")
    {
        if(sizeof($string)!=2 || $string[1]=="")
        {
            exit(23);
        }
        if($string[0]=="bool" && ($string[1]!="true" && $string[1]!="false"))
        {
            exit(23);
        }
        if($string[0]=="nil" && $string[1]!="nil")
        {
            exit(23);
        }
        print("\t\t<arg$arg_number type=\"$string[0]\">$string[1]</arg$arg_number>\n");
        return;
    }
    else
    {
        if($string[0]=='LF' || $string[0]=='GF' || $string[0]=='TF')
        {
            if(sizeof($string)==2)
            {
                $char=$string[1][0];
                if((strtoupper($char)<='Z' && strtoupper($char)>='A')|| $char=='_' || $char=='-' || $char=='$' || $char=='&' || $char=='%' || $char=='*' || $char =='!' || $char=='?') 
                {
                    if(str_contains($string[1],'&'))
                    {
                        $string[1]=str_replace("&","&amp;",$string[1]);
                    }
                    if(str_contains($string[1],'<'))
                    {
                        $string[1]=str_replace("<","&lt;",$string[1]);
                    }
                    if(str_contains($string[1],'>'))
                    {
                        $string[1]=str_replace(">","&gt;",$string[1]);
                    }
                    print("\t\t<arg$arg_number type=\"var\">$string[0]@$string[1]</arg$arg_number>\n");
                }
                else
                {
                    exit(23);
                }
            return;
            }
            else
            {
                exit(23);
            }
        }
        else
        {
            exit(23);
        }
    }
    return;
}
//function check and write on stdout arg with type label in XML format
function check_label($string,$arg_number)
{
    if(str_contains($string,'@'))
    {
        exit(23);
    }
    $char=$string[0];
    if((strtoupper($char)<='Z' && strtoupper($char)>='A')|| $char=='_' || $char=='-' || $char=='$' || $char=='&' || $char=='%' || $char=='*' || $char =='!' || $char=='?') 
    {
        if(str_contains($string,'&'))
        {
            $string=str_replace("&","&amp;",$string[1]);
        }
        if(str_contains($string,'<'))
        {
            $string=str_replace("<","&lt;",$string[1]);
        }
        if(str_contains($string,'>'))
        {
            $string=str_replace(">","&gt;",$string[1]);
        }
        print("\t\t<arg$arg_number type=\"label\">$string</arg$arg_number>\n");
    }
    else
    {
        exit(23);
    }
}
//function write on stdout end of instruction element
function end_instruction()
{
    print("\t</instruction>\n");
}

ini_set('display_errors','stderr');
//find out if there is some argument
if($argc>1)
{
    //if argument is not --help
    if($argv[1]!="--help")
        exit(10);
    //if argument is --help
    else
        print("Script (parse.php in langueage PHP 8.1) load source code in IPP-code from stdin \ncheck lexikal and syntax of code and output XML representation of program.\n");
        exit(0);

}
print("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"); //XML header
$line = fgets(STDIN);
while($line[0]=='#') //strip coments from the beginnig of input
{
    $line=fgets(STDIN);
}
$line=explode('#',$line);//gets rid of comments
$line[0]=trim($line[0]," ");
$line[0]=trim($line[0],"\n");

if($line[0]==".IPPcode22")//checking if there is good header
{
    echo("<program language=\"IPPcode22\">\n");
}
else
{
    print("exit");
    exit(21);
}

while(($line = fgets(STDIN)))//go line by line
{
    while($line[0]=='#') //strip coments from the beginnig of input
    {
        if(!($line=fgets(STDIN)))
        {
            exit(0);
        }
    }
    $line=explode('#',trim($line,"\n"));//gets rid of comments
    //vypisovanie komentarov
    //if(sizeof($line)!=1)
    //{
    //    print("$line[1]\n");
    //}
    $words=explode(' ',trim($line[0]," ")); //split line on separate elements
    //for all keywords control syntax
    $words[0]=strtoupper($words[0]);    //change first element(instruction) to upper case
    switch(strtoupper($words[0])) 
    {
        //deciding which instuction is on stdin and calling apropriate functions for each instruction
        case "MOVE":
        case "INT2CHAR":   
        case "STRLEN":
            if(sizeof($words)==3)
            {
            write_instruction($words[0]);
            check_var($words[1],1);
            check_symb($words[2],2);
            end_instruction();
            }
            else
            {
                exit(23);
            }
            break;
        case "READ":
        case "TYPE":
            if(sizeof($words)==3)
            {
            write_instruction($words[0]);
            check_var($words[1],1);
            check_type($words[2],2);
            end_instruction();
            }
            else
            {
                exit(23);
            }
            //<var> <type>
            break;
        case "DEFVAR":
        case "POPS":
            if(sizeof($words)==2)
            {
            write_instruction($words[0]);
            check_var($words[1],1);
            end_instruction();
            }
            else
            {
                exit(23);
            }
            //<var>
            break;
        case "PUSHS":
        case "WRITE":
        case "EXIT":
        case "DPRINT":
            if(sizeof($words)==2)
            {
            write_instruction($words[0]);
            check_symb($words[1],1);
            end_instruction();
            }
            else
            {
                print($words[2]);
                print("\n");
                print(sizeof($words));
                exit(23);
            }
            //<symb>
            break;
        
        case "ADD":
        case "SUB":
        case "MUL":
        case "IDIV":
        case "LT":
        case "GT":    
        case "EQ":     
        case "AND":
        case "OR":
        case "NOT":
        case "STR2INT": 
        case "CONCAT":
        case "GETCHAR":
        case "SETCHAR":
        case " ";
            if(sizeof($words)==3)
            {
            write_instruction($words[0]);
            check_var($words[1],1);
            check_symb($words[2],2);
            check_symb($words[3],3);
            end_instruction();
            }
            else
            {
                exit(23);
            }
            break;
            //<var> <symb1> <symb2>
        case "CALL":
        case "LABEL":
        case "JUMP":
            if(sizeof($words)==2)
            {
            write_instruction($words[0]);
            check_label($words[1],1);
            end_instruction();
            }
            else
            {
                exit(23);
            }
            //<label>
            break;
        case "JUMPIFEQ":
        case "JUMPIFNEQ":
            if(sizeof($words)==3)
            {
            write_instruction($words[0]);
            check_label($words[1],1);
            check_symb($words[2],2);
            check_symb($words[3],3);
            end_instruction();
            }
            else
            {
                exit(23);
            }
            //<label> <symb1> <symb2>
            break;
        case "CREATEFRAME":
        case "PUSHFRAME":
        case "POPFRAME":
        case "RETURN":
        case "BREAK":
            if(sizeof($words)==1)
            {
            write_instruction($words[0]);
            }
            else
            {
                exit(23);
            }        
            //none
            break;
        default:
            print("default");
            exit(22);
            break;
    }
}
//ending program 
printf("</program>\n");
?>