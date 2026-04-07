<?php

// src/Service/Columnizer.php

namespace App\Service;

class Columnizer
{
    private const COLUMN_NAMES = [
        'firstName' => 'First Name',
        'lastName' => 'Last Name',
        'prefName' => 'Preferred Name',
        'gender' => 'Gender',
        'prefPronouns' => 'Pronouns',
        'legalSex' => 'Legal Sex',
        'ethnicity' => 'Ethnicity',
        'prefGender' => 'Gender',
        'prefGenderPronoun' => 'Gender Pronoun',
        'prefHousing' => 'Preferred Housing',
        'homeAddress1' => 'Home Address 1',
        'homeAddress2' => 'Home Address 2',
        'homeCity' => 'Home City',
        'homeState' => 'Home State',
        'homeZIP' => 'Home ZIP',
        'homePhone' => 'Home Phone',
        'cellPhone' => 'Cell Phone',
        'email' => 'Email',
        'twitter' => 'Twitter',
        'school' => 'High School',
        'tshirtSize' => 'T-Shirt Size',
        'parent1FirstName' => 'Parent 1 First Name',
        'parent1LastName' => 'Parent 1 Last Name',
        'parent2FirstName' => 'Parent 2 First Name',
        'parent2LastName' => 'Parent 2 Last Name',
        'parent1Phone1' => 'Parent 1 Primary Phone',
        'parent2Phone1' => 'Parent 2 Primary Phone',
        'parent1Phone2' => 'Parent 1 Secondary Phone',
        'parent2Phone2' => 'Parent 2 Secondary Phone',
        'parent1Email' => 'Parent 1 Email',
        'parent2Email' => 'Parent 2 Email',
        'dateOfBirth' => 'Date of Birth',
        'ecFirstName' => 'Emergency Contact First Name',
        'ecLastName' => 'Emergency Contact Last Name',
        'ecRelationship' => 'Emergency Contact Relationship',
        'ecPhone1' => 'Emergency Contact Primary Phone',
        'ecPhone2' => 'Emergency Contact Secondary Phone',
        'currentConditions' => 'Current Conditions',
        'exerciseLimits' => 'Physical Activity Limits',
        'allergies' => 'Allergies',
        'dietRestrictions' => 'Diet Restrictions',
        'dietInfo' => 'Dietary Information',
        'dietSeverity' => 'Food Allergy Severity',
        'medAllergies' => 'Medical Allergies',
        'currentRx' => 'Prescription Medication',
        'approvedOtc' => 'Approved OTC',
        'depositMethod' => 'Deposit Method',
        'groupPhoto' => 'Purchased Group Photo',
        'counselorFirstName' => 'Counselor First Name',
        'counselorLastName' => 'Counselor Last Name',
        'totalOwed' => 'Total Owed',
        'totalPaid' => 'Total Paid',
        'dorm' => 'Dorm',
        'room' => 'Room',
        'letterGroup' => 'Letter Group',
        'regionGroup' => 'Region Group',
        'checkedIn' => 'Checked In',
        'missingForms' => 'Missing Forms',
        'position' => 'Position',
        'age' => 'Age',
        'alumniSite' => 'Alumni Site',
        'highSchool' => 'High School',
        'skillsOffered' => 'What skills do you offer the seminar?',
        'fiveWords' => 'Describe yourself in five words or fewer.',
        'favoriteTeam' => 'Which song best describes your leadership style? Explain why.',
        'volExp' => 'Please list any organizations, events, or nonprofit groups you have volunteered with in the past year.',
        'youthOrgs' => 'Please list all youth organizations you are currently affiliated with, or have been affiliated with in the past.',
        'reference' => 'Please provide the name, title, organization, and contact information of one professional reference.',
        'videoLink' => 'Video',
        'priorHoby' => 'Prior HOBY experience',
        'feedbackSummary' => 'Summary of feedback from facilitators or co-facilitator.',
        'aloneOrOthers' => 'Do you prefer to work alone or with others?',
        'leaderGreat' => 'What makes a leader great? Why?',
        'problemSolve' => 'Describe a time you had to problem solve quickly and what your solution was.',
        'pronouns' => 'Pronouns',
        'assignmentCheckIn' => 'Check In Assignment',
        'assignmentCheckInNotes' => 'Check In Assignment Notes',
        'assignmentClosingCeremonies' => 'Closing Ceremony Assignment',
        'assignmentClosingCeremoniesNotes' => 'Closing Ceremony Assignment Notes',
        'assignmentCheckOut' => 'Check Out Assignment',
        'assignmentCheckOutNotes' => 'Check Out Assignment Notes',
    ];

    public function readColumnName(string $colName): string
    {
        return self::COLUMN_NAMES[$colName] ?? $colName;
    }

    public function spitShowRow($entity, $col, $showifnull = false): ?string
    {
        $getter = str_starts_with($col, 'get') ? $col : 'get' . $col;

        $data = nl2br($entity->$getter());

        if ($data == '1') $data = 'Yes';
        elseif ($data == '0') $data = 'No';
        elseif ($data == '2') $data = 'Maybe';

        if (!$showifnull && empty($data)) return null;

        return "<tr><th>{$this->readColumnName($col)}</th><td>{$data}</td></tr>";
    }
}