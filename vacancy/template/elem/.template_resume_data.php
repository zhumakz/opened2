<?php
//echo '<pre>';
//print_r($GLOBALS['JM']['HTML']);
?>
<div id="job-data-org" class="job-data">
    <div class="elem">

        <div class="section-name"><?= $GLOBALS['JM']['LANG']['FORM']['SECTION_1_TITLE'] ?></div>
        <div class="section">
            <div>
                <b><?= $GLOBALS['JM']['LANG']['RESUME_TYPE'] ?></b>
                <?php 
                    if ($GLOBALS['JM']['HTML']['DATA_RESUME']['data']['VacancyId'] === '') echo $GLOBALS['JM']['LANG']['WISH_TAB'];
                    else
                        {
                        if ($GLOBALS['JM']['HTML']['DATA_RESUME']['data']['IsContest'] !== '') echo $GLOBALS['JM']['LANG']['CONTEST'];
                        else echo $GLOBALS['JM']['LANG']['VACANCY'];
                        }
                ?>
            </div>
            <div>
                <b><?= $GLOBALS['JM']['LANG']['DATE_REG'] ?></b>
                <?= $GLOBALS['JM']['HTML']['DATA_RESUME']['time'] ?>
            </div>
            <div>
                <b><?= $GLOBALS['JM']['LANG']['ORG'] ?></b>
                <?= $GLOBALS['JM']['HTML']['DATA_ORG'][$GLOBALS['JM']['HTML']['DATA_RESUME']['org']]['long_name'] ?>
            </div>
            
            <?php if ($GLOBALS['JM']['HTML']['DATA_RESUME']['data']['VacancyId'] === '' && !$GLOBALS['JM']['HTML']['DATA_RESUME']['data']['IsContest']): ?>
                <div>
                    <b><?= $GLOBALS['JM']['LANG']['TEMP_2'] ?></b>
                    <?= $GLOBALS['JM']['HTML']['DATA_RESUME']['data']['Temp_2'] ?>
                </div>
                <div>
                    <b><?= $GLOBALS['JM']['LANG']['TEMP_3'] ?></b>
                    <?= $GLOBALS['JM']['HTML']['DATA_RESUME']['data']['Temp_3'] ?>
                </div>
            <?php endif; ?>

            <?php if ($GLOBALS['JM']['HTML']['DATA_RESUME']['data']['VacancyId'] !== ''): ?>
                <div>
                    <b><?= $GLOBALS['JM']['LANG']['DEPARTMENT'] ?></b>
                    <?= $GLOBALS['JM']['HTML']['DEPARTAMENT_JOB'] ?>
                </div>
                <div>
                    <b><?= $GLOBALS['JM']['LANG']['POSITION'] ?></b>
                    <?= $GLOBALS['JM']['HTML']['POSITION_JOB'] ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($GLOBALS['JM']['HTML']['DATA_RESUME']['data']['IsContest']): ?>
            <div class="section-name"><?= $GLOBALS['JM']['LANG']['FORM']['SECTION_2_TITLE'] ?></div>
            <div class="section">
                <div>
                    <b><?= $GLOBALS['JM']['LANG']['FORM']['TEST_LANGUAGE'] ?></b>
                    <?= $GLOBALS['JM']['LANG']['FORM']['TEST_LANGUAGE_' . $GLOBALS['JM']['HTML']['DATA_RESUME']['data']['TestingLanguage']] ?>
                </div>
                <div>
                    <?php if($GLOBALS['JM']['HTML']['DATA_RESUME']['data']['Subject'] === ''): ?>
                        <b><?= $GLOBALS['JM']['LANG']['FORM']['SUBJECT'] ?> <span class="hand">!</span></b>
                        <?= $GLOBALS['JM']['HTML']['DATA_RESUME']['data']['SubjectHand'] ?>
                    <?php else: ?>
                        <b><?= $GLOBALS['JM']['LANG']['FORM']['SUBJECT'] ?></b>
                        <?= $GLOBALS['JM']['HTML']['LIST_SUBJECT'][$GLOBALS['JM']['HTML']['DATA_RESUME']['data']['Subject']] ?>
                    <?php endif; ?>                    
                </div>
          
             
            </div>
        <?php endif; ?>

        <div class="section-name"><?= $GLOBALS['JM']['LANG']['FORM']['SECTION_3_TITLE'] ?></div>
        <div class="section">
            <div>
                <b><?= $GLOBALS['JM']['LANG']['FORM']['FIRST_NAME'] ?></b>
                <?= $GLOBALS['JM']['HTML']['DATA_RESUME']['data']['FirstName'] ?>
            </div>
            <div>
                <b><?= $GLOBALS['JM']['LANG']['FORM']['SECOND_NAME'] ?></b>
                <?= $GLOBALS['JM']['HTML']['DATA_RESUME']['data']['SecondName'] ?>
            </div>
            <div>
                <b><?= $GLOBALS['JM']['LANG']['FORM']['BIRTH_DATE'] ?></b>
                <?= $GLOBALS['JM']['HTML']['DATA_RESUME']['data']['BirthDate'] ?>
            </div>
            <div>
                <b><?= $GLOBALS['JM']['LANG']['FORM']['IIN'] ?></b>
                <?= $GLOBALS['JM']['HTML']['DATA_RESUME']['data']['Iin'] ?>
            </div>
        </div>

        <div class="section-name"><?= $GLOBALS['JM']['LANG']['FORM']['SECTION_4_TITLE'] ?></div>
        <div class="section">
            <div>
                <b class="title"><?= $GLOBALS['JM']['LANG']['FORM']['KAZAKH_LEVEL'] ?></b>
                <?= $GLOBALS['JM']['HTML']['DATA_RESUME']['data']['KazakhLevel'] ?>
            </div>
            <div>
                <b><?= $GLOBALS['JM']['LANG']['FORM']['RUSSIAN_LEVEL'] ?></b>
                <?= $GLOBALS['JM']['HTML']['DATA_RESUME']['data']['RussianLevel'] ?>
            </div>
            <div>
                <b><?= $GLOBALS['JM']['LANG']['FORM']['ENGLISH_LEVEL'] ?></b>
                <?= $GLOBALS['JM']['HTML']['DATA_RESUME']['data']['EnglishLevel'] ?>
            </div>
        </div>

        <?php foreach ($GLOBALS['JM']['HTML']['DATA_RESUME']['data']['Education'] as $i => $data): ?>

            <div class="section-name"><?= $GLOBALS['JM']['LANG']['FORM']['SECTION_5_TITLE'] ?> №<?= $i + 1 ?></div>
            <div class="section">
                <div>
                    
                    <?php if($GLOBALS['JM']['HTML']['DATA_RESUME']['data']['Education'][$i]['EducationLevelId'] === ''): ?>
                        <b><?= $GLOBALS['JM']['LANG']['FORM']['EDU_LEVEL'] ?> <span class="hand">!</span></b>
                        <?= $GLOBALS['JM']['HTML']['DATA_RESUME']['data']['Education'][$i]['EducationLevelHand'] ?>
                    <?php else: ?>
                        <b><?= $GLOBALS['JM']['LANG']['FORM']['EDU_LEVEL'] ?></b>
                        <?= $GLOBALS['JM']['HTML']['LIST_EDUCATION_LVL'][$GLOBALS['JM']['HTML']['DATA_RESUME']['data']['Education'][$i]['EducationLevelId']] ?>
                    <?php endif; ?>
                    
                </div>
                <div>
                    <b><?= $GLOBALS['JM']['LANG']['FORM']['EDU_DATE_START'] ?></b>
                    <?= $GLOBALS['JM']['HTML']['DATA_RESUME']['data']['Education'][$i]['StartDate'] ?>
                </div>
                <div>
                    <b><?= $GLOBALS['JM']['LANG']['FORM']['EDU_DATE_END'] ?></b>
                    <?= $GLOBALS['JM']['HTML']['DATA_RESUME']['data']['Education'][$i]['FinishDate'] ?>
                </div>
                <div>

                    <?php if($GLOBALS['JM']['HTML']['DATA_RESUME']['data']['Education'][$i]['EducationOrganization'] === ''): ?>
                        <b><?= $GLOBALS['JM']['LANG']['FORM']['EDU_ORG_NAME'] ?> <span class="hand">!</span></b>
                        <?= $GLOBALS['JM']['HTML']['DATA_RESUME']['data']['Education'][$i]['EducationOrganizationHand'] ?>
                    <?php else: ?>
                        <b><?= $GLOBALS['JM']['LANG']['FORM']['EDU_ORG_NAME'] ?></b>
                        <?= $GLOBALS['JM']['HTML']['LIST_EDUCATION_ORG'][$GLOBALS['JM']['HTML']['DATA_RESUME']['data']['Education'][$i]['EducationOrganization']] ?>
                    <?php endif; ?>
                    
                </div>
                <div>
                    <b><?= $GLOBALS['JM']['LANG']['FORM']['EDU_COUNTRY'] ?></b>
                    <?= $GLOBALS['JM']['HTML']['LIST_COUNTRY'][$GLOBALS['JM']['HTML']['DATA_RESUME']['data']['Education'][$i]['Country']] ?>
                </div>
                <div>
                    
                    <?php if($GLOBALS['JM']['HTML']['DATA_RESUME']['data']['Education'][$i]['City'] === ''): ?>
                        <b><?= $GLOBALS['JM']['LANG']['FORM']['EDU_CITY'] ?> <span class="hand">!</span></b>
                        <?= $GLOBALS['JM']['HTML']['DATA_RESUME']['data']['Education'][$i]['CityHand'] ?>
                    <?php else: ?>
                        <b><?= $GLOBALS['JM']['LANG']['FORM']['EDU_CITY'] ?></b>
                        <?= $GLOBALS['JM']['HTML']['LIST_CITY'][$GLOBALS['JM']['HTML']['DATA_RESUME']['data']['Education'][$i]['City']] ?>
                    <?php endif; ?>
                    
                </div>
                <div>
                    <b><?= $GLOBALS['JM']['LANG']['FORM']['EDU_BOLASHAK'] ?></b>
                    
                    <?php if($GLOBALS['JM']['HTML']['DATA_RESUME']['data']['Education'][$i]['IsBolashak']): ?>
                        <?= $GLOBALS['JM']['LANG']['FORM']['EDU_BOLASHAK_YES'] ?>
                    <?php else: ?>
                        <?= $GLOBALS['JM']['LANG']['FORM']['EDU_BOLASHAK_NO'] ?>
                    <?php endif; ?>
                        
                </div>
                <div>
                    <b><?= $GLOBALS['JM']['LANG']['FORM']['EDU_SPECIALITY'] ?></b>
                    <?= $GLOBALS['JM']['HTML']['DATA_RESUME']['data']['Education'][$i]['Specialty'] ?>
                </div>
                <div>
                    <b><?= $GLOBALS['JM']['LANG']['FORM']['EDU_DIPLOM_NUMBER'] ?></b>
                    <?= $GLOBALS['JM']['HTML']['DATA_RESUME']['data']['Education'][$i]['DiplomaNumber'] ?>
                </div>
                <div>
                    <b><?= $GLOBALS['JM']['LANG']['FORM']['EDU_WORK_THEME'] ?></b>
                    <?= $GLOBALS['JM']['HTML']['DATA_RESUME']['data']['Education'][$i]['WorkTheme'] ?>
                </div>
                <div>
                    <b><?= $GLOBALS['JM']['LANG']['FORM']['EDU_NOTE'] ?></b>
                    <?= $GLOBALS['JM']['HTML']['DATA_RESUME']['data']['Education'][$i]['Note'] ?>
                </div>
            </div>
        
        <?php endforeach; ?>

        <div class="section-name"><?= $GLOBALS['JM']['LANG']['FORM']['SECTION_6_TITLE'] ?></div>
        <div class="section">
            <div>
                <b><?= $GLOBALS['JM']['LANG']['FORM']['EXPERIENCE_TOTAL'] ?></b>
                <?= $GLOBALS['JM']['HTML']['DATA_RESUME']['data']['TotalExperience'] ?>
            </div>
            <div>
                <b><?= $GLOBALS['JM']['LANG']['FORM']['EXPERIENCE_EDU'] ?></b>
                <?= $GLOBALS['JM']['HTML']['DATA_RESUME']['data']['EducationExperience'] ?>
            </div>
        </div>

        <div class="section-name"><?= $GLOBALS['JM']['LANG']['FORM']['SECTION_7_TITLE'] ?></div>
        <div class="section">
            <div>
                <b><?= $GLOBALS['JM']['LANG']['FORM']['CONTACT_PHONE'] ?></b>
                <?= $GLOBALS['JM']['HTML']['DATA_RESUME']['data']['Phone'] ?>
            </div>
            <div>
                <b><?= $GLOBALS['JM']['LANG']['FORM']['CONTACT_MAIL'] ?></b>
                <?= $GLOBALS['JM']['HTML']['DATA_RESUME']['data']['Email'] ?>
            </div>
            <div>
                
                <?php if($GLOBALS['JM']['HTML']['DATA_RESUME']['data']['ActualCity'] === ''): ?>
                    <b><?= $GLOBALS['JM']['LANG']['FORM']['CONTACT_CITY'] ?> <span class="hand">!</span></b>
                    <?= $GLOBALS['JM']['HTML']['DATA_RESUME']['data']['ActualCityHand'] ?>
                <?php else: ?>
                    <b><?= $GLOBALS['JM']['LANG']['FORM']['CONTACT_CITY'] ?></b>
                    <?= $GLOBALS['JM']['HTML']['LIST_CITY'][$GLOBALS['JM']['HTML']['DATA_RESUME']['data']['ActualCity']] ?>
                <?php endif; ?>
                
            </div>
        </div>

        <div class="section-name"><?= $GLOBALS['JM']['LANG']['FORM']['SECTION_8_TITLE'] ?></div>
        <div class="section">
            
            <?php foreach ($GLOBALS['JM']['HTML']['DATA_RESUME']['data']['SummaryFiles'] as $i => $file): ?>
            
                <div class="E-3-4-4-6-6-12">
                    <div class="title"></div>
                    <div>
                        <div class="file">
                            <a class="file" href="/vacancy/file.php?file=<?= $file ?>">
                                <?= $GLOBALS['JM']['LANG']['FILE_DOWNLOAD'] ?>
                            </a>
                        </div>
                    </div>
                </div>
            
            <?php endforeach; ?>
            
        </div>
        <br /><br />

    </div>

</div>
