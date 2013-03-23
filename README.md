Meebo Community IM REST API Implementation
========================================

This a generic PHP class that can hook-in to Meebo's Community IM Service

Contributions are welcome.



          Sample usage:

            try{
              $meeboIM = new meebo;
              $meeboIM->updateStatus(1234, 'Jumping Turtle');
            } catch (Exception $e) {
              $errors = $e->getMessage();
              echo $errors;
            }

