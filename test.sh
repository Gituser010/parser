test=1
OK=0
wrong=0
#way=parse-only/GENERATED/add
for way in parse-only/*;do
	echo $way
	for i in $way/*.src ; do
	 	j=`echo $i | awk -F/ '{ print $3}'| awk -F. '{print $1}'` # print $x zavisi od zanorenia
		#echo $i
		php8.1 parse.php <$i >outputs/my_$j.out 
		error=$?
		#echo "expected"
		#echo $way/$j
		#cat $way/$j.out
		#echo "my output"
		#cat outputs/my_$j.out
		differ=$(diff -E -w --suppress-common-lines "$way/$j.out" outputs/my_$j.out | grep 1 |cut -d" " -f1,2)
		if [ -n "$differ" ]
		then
			if [ $error -ne 0 ]
			then
				echo "test $test OK"
				((OK++))
			else
				echo "test $test:$j ERROR"
				((wrong++))
			fi
		else	
			echo "test $test OK"
			((OK++))
		fi
		((test++))
	done
done
all=0
((all=OK+wrong))
echo "all:$all good:$OK bad:$wrong"
