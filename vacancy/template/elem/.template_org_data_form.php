<div id="job-data-org" class="job-data">
    <div class="elem">
    
        <form id="resume" action="" enctype="multipart/form-data" method="post" confirm="<?= $GLOBALS['JM']['LANG']['FORM']['MESSAGE'] ?>">

            <div class="section-name"><?= $GLOBALS['JM']['LANG']['FORM']['SECTION_1_TITLE'] ?></div>
            <div class="section">

                    <?php if ($_GET['type'] === 'wish'): ?>
                        <!--<div class="E-12-12-12-12-12-12">
                            <div class="title"><?= $GLOBALS['JM']['LANG']['TEMP_1'] ?></div>
                        </div>-->
                    <?php endif; ?>

                <div class="E-12-12-12-12-12-12">
                    <div class="title"><?= $GLOBALS['JM']['LANG']['ORG'] ?></div>
                    <div class="text"><?= $GLOBALS['JM']['HTML']['TITLE'] ?></div>
                </div>

                    <?php if ($_GET['type'] === 'wish'): ?>
                        <!--<div class="E-8-8-8-6-12-12">
                            <div class="title"><?= $GLOBALS['JM']['LANG']['TEMP_2'] ?></div>
                            <div><input name="Temp_2" type="text" /></div>
                        </div>
                        <div class="E-4-4-4-6-12-12">
                            <div class="title"><?= $GLOBALS['JM']['LANG']['TEMP_3'] ?></div>
                            <div><input name="Temp_3" type="text" /></div>
                        </div>-->
                    <?php endif; ?>

                <?php if ($_GET['type'] === 'contest' || $_GET['type'] === 'vacancy'): ?>
                    <div class="E-12-12-12-12-12-12">
                        <div class="title"><?= $GLOBALS['JM']['LANG']['DEPARTMENT'] ?></div>
                        <div class="text"><?= $GLOBALS['JM']['HTML']['DATA_ORG'][$_GET['org']][$_GET['type']][$_GET['id']]['Department' . $GLOBALS['JM']['LOCAL_Xx']] ?></div>
                    </div>
                    <div class="E-12-12-12-12-12-12">
                        <div class="title"><?= $GLOBALS['JM']['LANG']['POSITION'] ?></div>
                        <div class="text"><?= $GLOBALS['JM']['HTML']['DATA_ORG'][$_GET['org']][$_GET['type']][$_GET['id']]['Name' . $GLOBALS['JM']['LOCAL_Xx']] ?></div>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($_GET['type'] === 'contest'): ?>
                <div class="section-name"><?= $GLOBALS['JM']['LANG']['FORM']['SECTION_2_TITLE'] ?></div>
                <div class="section file-elem">
                    <div class="E-6-6-6-6-12-12">
                        <div class="title"><?= $GLOBALS['JM']['LANG']['FORM']['TEST_LANGUAGE'] ?></div>
                        <div>
                            <select name="TestingLanguage">
                                <option value=""><?= $GLOBALS['JM']['LANG']['FORM']['CHANGE'] ?></option>
                                <option value="1"><?= $GLOBALS['JM']['LANG']['FORM']['TEST_LANGUAGE_1'] ?></option>
                                <option value="2"><?= $GLOBALS['JM']['LANG']['FORM']['TEST_LANGUAGE_2'] ?></option>
                                <option value="3"><?= $GLOBALS['JM']['LANG']['FORM']['TEST_LANGUAGE_3'] ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="E-6-6-6-6-12-12">
                        <div class="title"><?= $GLOBALS['JM']['LANG']['FORM']['SUBJECT'] ?></div>
                        <div>
                            <select name="Subject">
                                <option value=""><?= $GLOBALS['JM']['LANG']['FORM']['CHANGE'] ?></option>
                            
                                <?php foreach ($GLOBALS['JM']['HTML']['LIST_SUBJECT'] as $id => $name): ?>
                                    <option value="<?= $id ?>"><?= $name ?></option>
                                <?php endforeach; ?>

                            </select>
                            <input name="SubjectHand" type="hidden" value=""/>
                        </div>
                        <div class="hand-change">
                            <div>
                                <label class="checkbox">
                                    <input name="CheckboxSubjectHand" type="checkbox" />
                                    <b></b>
                                </label>
                            </div>
                            <div><?=$GLOBALS['JM']['LANG']['FORM']['SUBJECT_HAND']?></div>
                        </div>
                    </div>



</div>

<?php endif; ?>
            <div class="section-name"><?= $GLOBALS['JM']['LANG']['FORM']['SECTION_3_TITLE'] ?></div>
            <div class="section">
                <div class="E-3-3-6-6-12-12">
                    <div class="title"><?= $GLOBALS['JM']['LANG']['FORM']['FIRST_NAME'] ?></div>
                    <div><input name="FirstName" type="text" /></div>
                </div>
                <div class="E-3-3-6-6-12-12">
                    <div class="title"><?= $GLOBALS['JM']['LANG']['FORM']['SECOND_NAME'] ?></div>
                    <div><input name="SecondName" type="text" /></div>
                </div>
                <div class="E-2-2-4-4-5-12">
                    <div class="title"><?= $GLOBALS['JM']['LANG']['FORM']['BIRTH_DATE'] ?></div>
                    <div><input min="1900-01-01" max="2020-01-01" name="BirthDate" type="date" /></div>
                </div>
                <div class="E-4-4-8-8-7-12">
                    <div class="title"><?= $GLOBALS['JM']['LANG']['FORM']['IIN'] ?></div>
                    <div><input name="Iin" type="text" /></div>
                </div>
            </div>

            <div class="section-name"><?= $GLOBALS['JM']['LANG']['FORM']['SECTION_4_TITLE'] ?></div>
            <div class="section">
                <div class="E-4-4-4-12-12-12">
                    <div class="title"><?= $GLOBALS['JM']['LANG']['FORM']['KAZAKH_LEVEL'] ?></div>
                    <div><input name="KazakhLevel" type="text" /></div>
                </div>
                <div class="E-4-4-4-12-12-12">
                    <div class="title"><?= $GLOBALS['JM']['LANG']['FORM']['RUSSIAN_LEVEL'] ?></div>
                    <div><input name="RussianLevel" type="text" /></div>
                </div>
                <div class="E-4-4-4-12-12-12">
                    <div class="title"><?= $GLOBALS['JM']['LANG']['FORM']['ENGLISH_LEVEL'] ?></div>
                    <div><input name="EnglishLevel" type="text" /></div>
                </div>
            </div>

            <div class="section-name education-title"><?= $GLOBALS['JM']['LANG']['FORM']['SECTION_5_TITLE'] ?> ¹1</div>
            <div class="education-data section">
                <div class="E-4-4-4-4-12-12">
                    <div class="title"><?= $GLOBALS['JM']['LANG']['FORM']['EDU_LEVEL'] ?></div>
                    <div>
                        <select name="EducationLevel[]">
                            <option value=""><?= $GLOBALS['JM']['LANG']['FORM']['CHANGE'] ?></option>
                    
                            <?php foreach ($GLOBALS['JM']['HTML']['LIST_EDUCATION_LVL'] as $id => $name): ?>
                                <option value="<?= $id ?>"><?= $name ?></option>
                            <?php endforeach; ?>

                        </select>
                        <input name="EducationLevelHand[]" type="hidden" value=""/>
                    </div>
                    <div class="hand-change">
                        <div>
                            <label class="checkbox">
                                <input name="CheckboxEducationLevelHand[]" type="checkbox" />
                                <b></b>
                            </label>
                        </div>
                        <div><?= $GLOBALS['JM']['LANG']['FORM']['EDU_LEVEL_HAND'] ?></div>
                    </div>
                </div>

                <div class="E-4-4-4-4-6-12">
                    <div class="title"><?= $GLOBALS['JM']['LANG']['FORM']['EDU_DATE_START'] ?></div>
                    <div><input min="1900-01-01" max="2030-01-01" name="StartDate[]" type="date" /></div>
                </div>
                <div class="E-4-4-4-4-6-12">
                    <div class="title"><?= $GLOBALS['JM']['LANG']['FORM']['EDU_DATE_END'] ?></div>
                    <div><input min="1900-01-01" max="2030-01-01" name="FinishDate[]" type="date" /></div>
                </div>
                <div class="E-12-12-12-12-12-12">
                    <div class="title"><?= $GLOBALS['JM']['LANG']['FORM']['EDU_ORG_NAME'] ?></div>
                    <div>
                        <select name="EducationOrganization[]">
                            <option value=""><?= $GLOBALS['JM']['LANG']['FORM']['CHANGE'] ?></option>
                    
                            <?php foreach ($GLOBALS['JM']['HTML']['LIST_EDUCATION_ORG'] as $id => $name): ?>
                                <option value="<?= $id ?>"><?= $name ?></option>
                            <?php endforeach; ?>

                        </select>
                        <input name="EducationOrganizationHand[]" type="hidden" value=""/>
                    </div>
                    <div class="hand-change">
                        <div>
                            <label class="checkbox">
                                <input name="CheckboxEducationOrganizationHand[]" type="checkbox" />
                                <b></b>
                            </label>
                        </div>
                        <div><?= $GLOBALS['JM']['LANG']['FORM']['EDU_ORG_NAME_HAND'] ?></div>
                    </div>
                </div>
                <div class="E-5-5-4-12-12-12">
                    <div class="title"><?= $GLOBALS['JM']['LANG']['FORM']['EDU_COUNTRY'] ?></div>
                    <div>
                        <select name="Country[]">
                            <option value=""><?= $GLOBALS['JM']['LANG']['FORM']['CHANGE'] ?></option>
                        
                            <?php foreach ($GLOBALS['JM']['HTML']['LIST_COUNTRY'] as $id => $name): ?>
                                <option value="<?= $id ?>"><?= $name ?></option>
                            <?php endforeach; ?>

                        </select>
                    </div>
                </div>
                <div class="E-5-5-5-8-8-12">
                    <div class="title"><?= $GLOBALS['JM']['LANG']['FORM']['EDU_CITY'] ?></div>
                    <div>
                        <select name="City[]" disabled="disabled"
                                mes-country-empty="<?= $GLOBALS['JM']['LANG']['FORM']['EDU_CITY_NEED_LOAD'] ?>"
                                mes-load-city="<?= $GLOBALS['JM']['LANG']['FORM']['LOAD'] ?>"
                                mes-load-error="<?= $GLOBALS['JM']['LANG']['FORM']['LOAD_ERROR'] ?>"
                                mes-default="<?= $GLOBALS['JM']['LANG']['FORM']['CHANGE'] ?>">
                            <option value=""><?= $GLOBALS['JM']['LANG']['FORM']['EDU_CITY_NEED_LOAD'] ?></option>
                        </select>
                        <input name="CityHand[]" type="hidden" value=""/>
                    </div>
                    <div class="hand-change">
                        <div>
                            <label class="checkbox">
                                <input name="CheckboxCityHand[]" type="checkbox" />
                                <b></b>
                            </label>
                        </div>
                        <div><?= $GLOBALS['JM']['LANG']['FORM']['EDU_CITY_NAND'] ?></div>
                    </div>
                </div>
                <div class="E-2-2-3-4-4-12">
                    <div class="title"><?= $GLOBALS['JM']['LANG']['FORM']['EDU_BOLASHAK'] ?></div>
                    <div>
                        <select name="IsBolashak[]">
                            <option value="0"><?= $GLOBALS['JM']['LANG']['FORM']['EDU_BOLASHAK_YES'] ?></option>
                            <option value="1"><?= $GLOBALS['JM']['LANG']['FORM']['EDU_BOLASHAK_NO'] ?></option>
                        </select>
                    </div>
                </div>
                <div class="E-12-12-12-12-12-12">
                    <div class="title"><?= $GLOBALS['JM']['LANG']['FORM']['EDU_SPECIALITY'] ?></div>
                    <div><input name="Specialty[]" type="text" /></div>
                </div>
                <div class="E-2-3-3-3-12-12">
                    <div class="title"><?= $GLOBALS['JM']['LANG']['FORM']['EDU_DIPLOM_NUMBER'] ?></div>
                    <div><input name="DiplomaNumber[]" type="text" /></div>
                </div>
                <div class="E-10-9-9-9-12-12">
                    <div class="title"><?= $GLOBALS['JM']['LANG']['FORM']['EDU_WORK_THEME'] ?></div>
                    <div><input name="WorkTheme[]" type="text" /></div>
                </div>
                <div class="E-12-12-12-12-12-12">
                    <div class="title"><?= $GLOBALS['JM']['LANG']['FORM']['EDU_NOTE'] ?></div>
                    <div class="textarea"><textarea name="Note[]"></textarea></div>
                </div>
                <a class="hidden delete-education red" confirm="<?= $GLOBALS['JM']['LANG']['FORM']['EDU_DELETE_CONFIRM'] ?>">
                    <?= $GLOBALS['JM']['LANG']['FORM']['EDU_DELETE'] ?> ¹1
                </a>
            </div>

            <div class="section-name education-title"><?= $GLOBALS['JM']['LANG']['FORM']['SECTION_5_TITLE'] ?> ¹2</div>
            <div class="section">
                <a class="add-education" confirm="<?= $GLOBALS['JM']['LANG']['FORM']['EDU_ADD_CONFORM'] ?>">
                    <?= $GLOBALS['JM']['LANG']['FORM']['EDU_ADD_BUTTON'] ?>
                </a>
            </div>

            <div class="section-name"><?= $GLOBALS['JM']['LANG']['FORM']['SECTION_6_TITLE'] ?></div>
            <div class="section">
                <div class="E-3-4-4-6-6-12">
                    <div class="title"><?= $GLOBALS['JM']['LANG']['FORM']['EXPERIENCE_TOTAL'] ?></div>
                    <div><input name="TotalExperience" type="text" /></div>
                </div>
                <div class="E-3-4-4-6-6-12">
                    <div class="title"><?= $GLOBALS['JM']['LANG']['FORM']['EXPERIENCE_EDU'] ?></div>
                    <div><input name="EducationExperience" type="text" /></div>
                </div>
            </div>

            <div class="section-name"><?= $GLOBALS['JM']['LANG']['FORM']['SECTION_7_TITLE'] ?></div>
            <div class="section">
                <div class="E-3-4-4-6-6-12">
                    <div class="title"><?= $GLOBALS['JM']['LANG']['FORM']['CONTACT_PHONE'] ?></div>
                    <div><input name="Phone" type="text" /></div>
                </div>
                <div class="E-3-4-4-6-6-12">
                    <div class="title"><?= $GLOBALS['JM']['LANG']['FORM']['CONTACT_MAIL'] ?></div>
                    <div><input name="Email" type="text" /></div>
                </div>
                <div class="E-3-4-4-12-12-12">
                    <div class="title"><?= $GLOBALS['JM']['LANG']['FORM']['CONTACT_CITY'] ?></div>
                    <div>
                        <select name="ActualCity">
                            <option value=""><?= $GLOBALS['JM']['LANG']['FORM']['CHANGE'] ?></option>
                        
                            <?php foreach ($GLOBALS['JM']['HTML']['LIST_CITY'] as $id => $name): ?>
                                <option value="<?= $id ?>"><?= $name ?></option>
                            <?php endforeach; ?>

                        </select>
                        <input name="ActualCityHand" type="hidden" value=""/>
                    </div>
                    <div class="hand-change">
                        <div>
                            <label class="checkbox">
                                <input name="CheckboxActualCityHand" type="checkbox" />
                                <b></b>
                            </label>
                        </div>
                        <div><?= $GLOBALS['JM']['LANG']['FORM']['CONTACT_CITY_HAND'] ?></div>
                    </div>
                </div>
            </div>

            <div class="section-name"><?= $GLOBALS['JM']['LANG']['FORM']['SECTION_8_TITLE'] ?></div>
            <div class="section multifile-elem" item="10">
                <div class="E-3-4-4-6-6-12">
                    <div class="title"></div>
                    <div>
                        <div class="file">
                            <a class="file-add"><?=$GLOBALS['JM']['LANG']['FORM']['FILE_ADD_BUTTON']?></a>
                            <a class="file-delete red">X</a>
                          <div class="file-name" message="<?=$GLOBALS['JM']['LANG']['FORM']['FILE_MESSAGE']?>"></div>
                            <input 
                                name="SummaryFiles[]" 
                                type="file"
                                accept="image/gif,
                                        image/jpeg,
                                        image/png,
                                        image/tiff, 
                                        application/pdf, 
                                        application/msword, 
                                        application/vnd.openxmlformats-officedocument.wordprocessingml.document, 
                                        application/vnd.ms-excel, 
                                        application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                            />
                        </div>
                    </div>
<div class="hand-change">
                        <div>
                            <label class="checkbox">
                                <input name="CheckboxDjcAgree" type="checkbox" />
                                <b></b>
                            </label>
                        </div>
                        <div><?= $GLOBALS['JM']['LANG']['FORM']['AGREE'] ?></div>
                    </div>
                </div>
            </div>
            <div class="section-name"></div>
            <div class="section captcha">

                <div>
                    <div class="g-recaptcha" data-sitekey="<?= $GLOBALS['JM']['CAPCHA_FRONTEND'] ?>"></div>
                    <div class="text-danger" id="recaptchaError"></div>
                </div>
            </div>
            
            <input type="hidden" name="FORM_RESUME" />
            <a class="send-form big"><?= $GLOBALS['JM']['LANG']['FORM']['SEND_RESUME'] ?></a>
        </form>
        
    </div>

</div>