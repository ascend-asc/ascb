<?php
require_once '../config/config.php';
require_once '../app/core/Database.php';

$db = Database::getInstance();

$updates = [
    // Basic Ed
    ['Elementary Department', "- Established in 1968"],
    ['Junior High School', ""],
    ['Senior High School', ""],
    // College
    ['College of Accountancy Education (CAE)', "- Bachelor of Science in Accountancy"],
    [
        'College of Business Administration and Education (CBAE)',
        "- Bachelor of Science in Commerce (BSC)\n- Bachelor of Science in Accounting Technology\n- Bachelor of Science in Business Administration\n- 2-year Secretarial Course"
    ],
    [
        'College of Computer Education (CCE)',
        "- Bachelor of Science in Information Systems (BSIS)\n- Bachelor of Science in Information Technology (BSIT)\n- Bachelor of Science in Computer Science (BSCS)"
    ],
    ['College of Criminal Justice Education (CCJE)', "- Bachelor of Science in Criminology (BSCrim)"],
    [
        'College of Teacher Education (CTE)',
        "- Bachelor of Science in Elementary Education (BSEEd)\n- Secondary Education / other teacher-ed tracks, if offered \xe2\x80\x94 confirm with the Dean\xe2\x80\x99s office"
    ],
    // Diploma
    [
        'Diploma Business of Operation Technology ()',
        "- Ladderized program leading to Bachelor of Science in Business Administration major in Marketing Management"
    ],
    [
        'Diploma of Security Operation Technology (DSOT)',
        "- Ladderized program leading to Bachelor of Science in Criminology"
    ],
    [
        'Diploma Information System Technology (DIST)',
        "- Ladderized program leading to Bachelor of Science in Information Systems"
    ],
    [
        'Diploma Information Technology (DIT)',
        "- Ladderized program leading to Bachelor of Science in Information Technology"
    ],
];

$results = [];
foreach ($updates as [$name, $desc]) {
    $db->query("UPDATE programs SET description = :desc WHERE name = :name");
    $db->bind(':desc', $desc);
    $db->bind(':name', $name);
    $ok = $db->execute();
    $results[] = ($ok ? '✅' : '❌') . " " . htmlspecialchars($name);
}

echo "<pre>" . implode("\n", $results) . "\n\nDone. Delete this file.</pre>";
?>