<?php
declare(strict_types=1);
function e(mixed $v): string{return htmlspecialchars((string)($v??''),ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8');}
function money(mixed $v): string{return '$'.number_format((float)$v,0,',','.');}
function csrf(): void {echo '<input type="hidden" name="csrf" value="'.e($_SESSION['csrf']).'">';}
