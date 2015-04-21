<?php
    class CodeRetour extends SplEnum {
        const __default = -1;
        const ALLCLEAR = 0;
        const MSGPARAMS = 1;
        const NODATA = 2;
        const CONNECTFAILED = 3;
        const WRONGTOKEN = 4;
        const WRONGCREDS = 5;     
        const WRONGACTION = 6;
    }
?>