<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-[#3E3E3E]">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Payroll
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Manage Payroll</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">

      <?php if ($this->session->flashdata('success')) : ?>
        <div class="col-md-12">
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> Success!</h4>
            <?php echo $this->session->flashdata('success'); ?>
          </div>
        </div>
      <?php elseif ($this->session->flashdata('error')) : ?>
        <div class="col-md-12">
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> Failed!</h4>
            <?php echo $this->session->flashdata('error'); ?>
          </div>
        </div>
      <?php endif; ?>

      <div class="col-xs-12">
        <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
          <div class="box-header">
            <h3 class="box-title text-white">Staff Payroll</h3>
            <div class="flex justify-end mt-3 ">
                <a href="<?php echo base_url(); ?>payroll"  class="btn justify-end btn-primary border-0 bg-[#DA7F00] hover:bg-[#DA7F00]">Back</a>
			
            </div>
          </div>
          <!-- /.box-header -->

<form action="<?php echo base_url(); ?>payroll/update" method="POST">
    <input type="hidden" name="period" value="<?php echo $period ?>" />
    
<div class="container px-4 mx-auto sm:px-8">
    <div class="py-8">
        <button type="submit" class="bg-[#DA7F00] hover:bg-orange-600 p-4 px-6 rounded-lg">
            Save
        </button>
        <div class="px-4 py-4 -mx-4 overflow-x-auto sm:-mx-8 sm:px-8">
            <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">

                <table class="min-w-full leading-normal overflow-x-auto  scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                    <thead>
                    <tr>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                            </th>
                           
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-blue-50 border-b border-gray-200">
                            </th>
                            <th scope="col" colspan="4" class="text-center px-5 py-3 text-sm font-normal  text-gray-800 uppercase bg-green-50 border-b border-gray-200">
                                Additions
                            </th>

                            <th scope="col" colspan="4" class="justify-center px-5 py-3 text-sm font-normal text-center text-gray-800 uppercase bg-red-50 border-b border-gray-200">
                                Deductions
                            </th>
                           
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-green-100 border-b border-gray-200">
                                
                            </th>
                           
                           
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                            </th>
                        </tr>
                        <tr>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                                S/N
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                                Date
                            </th>
                           
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                                Staff
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                                Account
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                                Basic Salary
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                                Housing (&#8358;)
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                                Transport (&#8358;)
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                                Utility (&#8358;)
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                                Wardrobe (&#8358;)
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                                Medical (&#8358;)
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                                Meal Subsidy (&#8358;)
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-blue-50 border-b border-gray-200">
                                Gross Income (&#8358;)
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-green-50 border-b border-gray-200">
                                Advance Salary (&#8358;)
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-green-50 border-b border-gray-200">
                                Loans (&#8358;)
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-green-50 border-b border-gray-200">
                                Commission (&#8358;)
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-green-50 border-b border-gray-200">
                                Others (&#8358;)
                            </th>

                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-red-50 border-b border-gray-200">
                                Advance Salary (&#8358;)
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-red-50 border-b border-gray-200">
                                Loans (&#8358;)
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-red-50 border-b border-gray-200">
                                Commission (&#8358;)
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-red-50 border-b border-gray-200">
                                Others (&#8358;)
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-green-100 border-b border-gray-200">
                                Net Received (&#8358;)
                            </th>
                           
                           
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                            </th>
                        </tr>
                       
                    </thead>
                    <tbody>
                    <?php
                        $total_gross = 0;
                        $total_net = 0;
                        $total_advance_addition = 0;
                        $total_others_deduction = 0;
                    ?>
                    <?php
                        if (isset($payrolls)) :
                            $i = 1;
                            foreach ($payrolls as $cnt) :
                        ?>
                        <?php 
                            $gross = $cnt['salary'] + $cnt['housing'] +
                                     $cnt['transport'] + $cnt['utility'] +
                                     $cnt['wardrobe'] + $cnt['medical'] +
                                     $cnt['meal_subsidy'];

                            $additions = $cnt['addition_advance_salary'] + $cnt['addition_loans'] + $cnt['addition_others'];

                            $deductions = $cnt['deduction_advance_salary'] + $cnt['deduction_loans'] + $cnt['deduction_others'];

                            $net = $gross + $additions - $deductions;

                            $total_advance_addition += $cnt['addition_advance_salary'];
                            $total_others_deduction += $cnt['deduction_others'];
                            $total_gross += $gross;
                            $total_net += $net;
                        ?>
                    
                        <tr>
                        
                            <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    <?php echo $i ?>
                                </p>
                            </td>
                            <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                                <p class="text-gray-900 whitespace-no-wrap">
                                   <input type="date" name="date_<?php echo $cnt['id'] ?>"  value="<?php echo $cnt['date'] ?>" />
                                </p>
                            </td>
                            <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                                <div class="flex items-center">
                                    <div class="ml-3">
                                        <p class="text-gray-900 whitespace-no-wrap">
                                        <?php echo $cnt['staff_name'] ?>
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    <?php echo $cnt['bank'] ?>
                                </p>
                                <p class="text-gray-900 whitespace-no-wrap">
                                    <?php echo $cnt['account'] ?>
                                </p>
                            </td>
                            <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    <input name="salary_<?php echo $cnt['id'] ?>" type="number"  value="<?php echo $cnt['salary'] ?>"  />
                                </p>
                            </td>
                            <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    <input name="housing_<?php echo $cnt['id'] ?>" type="number"  value="<?php echo $cnt['housing'] ?>"  />
                                </p>
                            </td>
                            <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    <input name="transport_<?php echo $cnt['id'] ?>" type="number"  value="<?php echo $cnt['transport'] ?>"  />
                                </p>
                            </td>
                            <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    <input name="utility_<?php echo $cnt['id'] ?>" type="number"  value="<?php echo $cnt['utility'] ?>"  />
                                </p>
                            </td>
                            <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    <input name="wardrobe_<?php echo $cnt['id'] ?>" type="number"  value="<?php echo $cnt['wardrobe'] ?>"  />
                                </p>
                            </td>
                            <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    <input name="medical_<?php echo $cnt['id'] ?>" type="number"  value="<?php echo $cnt['medical'] ?>"  />
                                </p>
                            </td>
                            <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    <input name="meal_subsidy_<?php echo $cnt['id'] ?>" type="number"  value="<?php echo $cnt['meal_subsidy'] ?>"  />
                                </p>
                            </td>
                            <td class="px-5 py-5 text-sm bg-blue-50 border-b border-gray-200 w-6">
                                <p id="gross_income_<?php echo $cnt['id'] ?>" class="text-gray-900 whitespace-no-wrap">
                                <input class="bg-blue-50" type="text" readonly 
                                        id="gross_<?php echo $cnt['id'] ?>" 
                                        value="<?php echo number_format($gross, 2) ?>" />
                                </p>
                            </td>
                            <td class="px-5 py-5 text-sm bg-green-50 border-b border-gray-200">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    <input name="addition_advance_salary_<?php echo $cnt['id'] ?>" type="number"  value="<?php echo $cnt['addition_advance_salary'] ?>"  />
                                </p>
                            </td>
                            <td class="px-5 py-5 text-sm bg-green-50 border-b border-gray-200">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    <input name="addition_loans_<?php echo $cnt['id'] ?>" type="number"  value="<?php echo $cnt['addition_loans'] ?>"  />
                                </p>
                            </td>
                            <td class="px-5 py-5 text-sm bg-green-50 border-b border-gray-200">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    <input name="addition_commission_<?php echo $cnt['id'] ?>" type="number"  value="<?php echo $cnt['addition_commission'] ?>"  />
                                </p>
                            </td>
                            <td class="px-5 py-5 text-sm bg-green-50 border-b border-gray-200">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    <input name="addition_others_<?php echo $cnt['id'] ?>" type="number"  value="<?php echo $cnt['addition_others'] ?>"  />
                                </p>
                            </td>
                            <td class="px-5 py-5 text-sm bg-red-50 border-b border-gray-200">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    <input name="deduction_advance_salary_<?php echo $cnt['id'] ?>" type="number"  value="<?php echo $cnt['deduction_advance_salary'] ?>"  />
                                </p>
                            </td>
                            <td class="px-5 py-5 text-sm bg-red-50 border-b border-gray-200">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    <input name="deduction_loans_<?php echo $cnt['id'] ?>" type="number"  value="<?php echo $cnt['deduction_loans'] ?>"  />
                                </p>
                            </td>
                            <td class="px-5 py-5 text-sm bg-red-50 border-b border-gray-200">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    <input name="deduction_commission_<?php echo $cnt['id'] ?>" type="number"  value="<?php echo $cnt['deduction_commission'] ?>"  />
                                </p>
                            </td>
                            <td class="px-5 py-5 text-sm bg-red-50 border-b border-gray-200">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    <input name="deduction_others_<?php echo $cnt['id'] ?>" type="number"  value="<?php echo $cnt['deduction_others'] ?>"  />
                                </p>
                            </td>
                            <td class="px-5 py-5 text-sm bg-green-100 border-b border-gray-200">
                                <p class="text-gray-900 whitespace-no-wrap">
                                <input class="bg-green-100" type="text" readonly
                                        id="net_<?php echo $cnt['id'] ?>"
                                        value="<?php echo number_format($net, 2) ?>" />
                                </p>
                            </td>
                           
                            
                            <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                                
                            </td>
                         
                        </tr>
                   
                             
                    <?php
                        $i++;
                        endforeach;
                    endif;
                    ?>
                    <tr>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                                TOTAL
                            </th>
                           
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                                <?php echo $salary_sum ?>
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-blue-50 border-b border-gray-200">
                                <?php echo $total_gross ?>
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-green-50 border-b border-gray-200">
                                <?php echo $total_advance_addition ?>
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-green-50 border-b border-gray-200">
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-green-50 border-b border-gray-200">
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-green-50 border-b border-gray-200">
                            </th>

                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-red-50 border-b border-gray-200">
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-red-50 border-b border-gray-200">
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-red-50 border-b border-gray-200">
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-red-50 border-b border-gray-200">
                             <?php echo $total_others_deduction ?>
                            </th>
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-green-100 border-b border-gray-200">
                            <?php echo $total_net ?>
                            </th>
                           
                           
                            <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                            </th>
                        </tr>

                   
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
</div>
<input 
    name="payrolls" 
    type="hidden"
    value="<?php foreach ($payrolls as $payroll) {
        echo $payroll['id'].",";
    }?>"  />

<div class=" ml-20 rounded-lg px-4 py-4 -mx-4 overflow-x-auto sm:-mx-8 sm:px-8">
    <h3 class="ml-12 my-4 py-4">NOTE TO THE ACCOUNT.</h3>
    <table class=" w-1/2 md:w-full leading-normal overflow-x-auto ml-12 rounded-lg scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
        <thead>
            <tr>
                <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                    Name
                </th>
                <th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
                    Comment
                </th>
            </tr>
        </thead>

        <tbody>
            <?php
                if (isset($payrolls)) :
                    foreach ($payrolls as $s) :
            ?>
                      
                    
            <tr>
            
                <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                    <p class="text-gray-900 whitespace-no-wrap">
                        <?php echo $s['staff_name'] ?>
                    </p>
                </td>
                <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                    <p class="text-gray-900 whitespace-no-wrap">
                        <textarea 
                            cols="30" 
                            class="border-b border-gray-300" 
                            rows="5" 
                            name="remark_<?php echo $s['id'] ?>"><?php echo $s['remark'] ?></textarea>

                    </p>
                </td>
                </tr>

            <?php
                endforeach;
            endif;
            ?>

                          

        </tbody>

    </table>
</div>

</form>

          <div
           class="box-body">
            <div class="table-responsive">

           
            </div>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
