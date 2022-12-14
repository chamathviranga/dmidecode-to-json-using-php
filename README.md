# dmidecode-to-json-using-php

## What is dmidecode
dmidecode  is  a  tool  for dumping a computer's DMI (some say SMBIOS) table contents in a human-readable format. This table contains a de‐
scription of the system's hardware components, as well as other useful pieces of information such as  serial  numbers  and  BIOS  revision.
Thanks  to  this  table,  you can retrieve this information without having to probe for the actual hardware.  While this is a good point in
terms of report speed and safeness, this also makes the presented information possibly unreliable.

The DMI table doesn't only describe what the system is currently made of, it also can report the possible evolutions (such as  the  fastest
supported CPU or the maximal amount of memory supported).

SMBIOS  stands for System Management BIOS, while DMI stands for Desktop Management Interface. Both standards are tightly related and devel‐
oped by the DMTF (Desktop Management Task Force).

As you run it, dmidecode will try to locate the DMI table. It will first try to read the DMI table from sysfs, and  next  try  reading  di‐
rectly  from memory if sysfs access failed.  If dmidecode succeeds in locating a valid DMI table, it will then parse and display
a list of records.

## dmidecode Examples

```
# Get memory information
sudo dmidecode -t 17
```
```
# Get processor information
sudo dmidecode -t processor
```
```
# About dmidecode
man dmidecode
```
## About the php script
The script will help to get dmideocde output in to webpage / API response as a json response

## Code flow
```
func name : toJson
----------------------------------------------------------------------------------------------------------
params:
    command (array) : use can pass dmidecode command manualy

vars:
    response (array)
    segmentIndex (int) : to avoid conflict if segments have same name : default = 0
		innerSegmentName (string)
		segmentName (string)
    exec_command (string)
  
    dmidecode output (array) =  execute dmidecode command
    
    filtered output (array) = remove first four elements from output array
    
    unset old dimdecode output (no more need that array)
    
    loop filtered fOpt : foreach( rowLineNumber ,  line ) {
    
        if ( line start with "Handle" ) {

            if ( segmentIndex = segmentName == fOpt[rowLineNumber + 1] + " " + segmentIndex ) {
                $segmentIndex +1 
            } else {
                $segmentIndex = 0
            }

            segmentName = fOpt[rowLineNumber + 1] + " " + segmentIndex

            skip loop from here
        }
        
        // line will be like this -> ( key: value )
        matches (array : [0 => line, 1 => key, 2=> value]) = preg match with "any: key"
        
        if ( matches found ) {
            
            innerSegmentName = null
            responsep[segmentName][ matches[1] ] = matches[2]
            
        } else if ( line last char == ':' ) {
        
            // To handle multivalue segments
        
            innerSegmentName = remove ':' from line and set
            response [segmentName][innerSegmentName] = []
          
        }
        
        if ( line first char == tab space and not empty innerSegmentName and innerSegmentName != current line ) {
          
            response[segmentName][innerSegmentName][] = line
          
        }
        
        
    }
    
    print json encoded response
        
            

```


