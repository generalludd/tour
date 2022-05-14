# Steps for Testing Releases

## Update your environment
* Open the project in your IDE (VS Code, PhpStorm)
* Open a terminal window (In VS Code there's a Terminal menu)
* type `pwd` in the terminal window. 
  * type `ls -la` to see what is in the current directory
  * if there is a directory called `src` then type `cd src`
  * if there is a `system` and an `application` directory in the list you are already in the right place.
* Pull the latest code by typing `git pull`

## Start up Docker
* To start up the container in docker do the following.
* type `cd ..` in the terminal to move up one directory
* type `docker-compose up -d` to launch docker
* wait about 3 minutes and then go to `tours.test` to see if the site has loaded properly.

## Authentication & Account
* Users should be able to log in http://tours.test/auth

## Utility Menu

Once logged in, there should be four buttons in the top right "Refresh Page", "Backup", "Account Info", "Log Out"
* Reload This Page simply reloads the page. 
* Backup creates a backup of the database and downloads it to your computer. 
* Account Info loads a page that shows an option to change passwords and ,for some users, to be able to add a new user. These are rarely used. 
* Log out should return you to the login page. Clicking the back arrow will return you to the authorization page. 

## Main Navigation

There are 3 buttons and one search field in the main navigation. "Home", "Find People", "Tours", "People"
![image](https://user-images.githubusercontent.com/571478/168404159-a5a40d19-708e-4f22-b190-1e1e3f195f8c.png)

## Tours
* Tour list should appear by start date and without error. 

### Add a tour
* Should be able to add a tour without error
* Should be able to add a letter for the tour. 
* Should be able to resize message window
* Should be able to save without error.
* Should be able to re-edit without error.
* Should be able to delete.

#### Adding tourists and payers. 
* Find a person in the find people field 
* Click on Join Tour
* Should show a list of available tours that the person has not been on yet. 
* Add as payer should show a page with payment options
* Add as tourist should result in a pop-up dialog with existing payers (or nothing if there are no payers).  
* When clicking on existing payers, it should show the tour list with the person's name added under the existing payer
* When clicking on Tour History, it should not show an error and should show an accurate list of tours the person has been on past and present. 

#### Adding Payments
* Go to the tour page and edit a payment
* Add a payment that is almost the amount of the total
* Go back to the tour list
* Check the subtotals and amount due for the person you edited.
* Test out reimbursements and changes to room selection sizes to be sure that the total due is correctly calculated

#### Creating Welcome Letters
* Go to the tour page
* Click on "Letter Templates"
* Add a new letter template with a name such as "Welcome Letter"
* Go back to the tour page and click on "Send Letter" next to one of the participants
* You should see a page with the content of the template.
* You should be able to edit the template from this page.
* You should be able to click on the date and the name fields to change the date and salutation for this letter.
* You should be able to add an additional note.
* The bottom of the letter should show the details of the person's tour including payments, amount due, tourists on the ticket, and other details. 
* If you print preview, it should show a letter with proper margins. 

# Hotels
* Go to a tour and click "Hotels"
* Click "Add Hotel"
* Add a hotel and enter 1 as the stay and click save.
* Click "Roomates"
* Click "Add Room"
* Click on "Double" and change to "Single"
* Tab out of the field
* Click on "Add Roommate"
* You should have a list of available roommates.
* Click "Add Room"
* click "Add Roommate"
* The list should not include the person in the first room.
* Refresh the page to be sure the roommate list and room sizes stick. 
* Click "Add Stay (Hotel)"
* Create a new hotel.
* Click "Roommates"
* Click "Duplicate from Previous stay
* Choose print: the output should be reasonably formatted without any buttons or errors. 


## People
* Click on "Home" or "People" to view the addressbook of people. 
* There should be no errors on the landing page. 
* There should be a list of people. 
* In the "Find People" field typing the start of a name like "Julian". You should see a list of people and possible actions (show and join tour). 

### Filtering
* Clicking on a letter to filter on that letter of the alphabet should result in a list with last names starting only with that letter.
* Clicking on "Filter Results" should not result in an error 
* ![image](https://user-images.githubusercontent.com/571478/168404077-d069a17b-d18f-4a4b-a124-df631bef2176.png)
* Filters should produce expected results on the resulting form
  * Example: Showing only veterans should result in a list where every record should have a "Yes" in the Is Vet column. 

### Exporting
* Exporting records should result in a usable spreadsheet (no columns out of place, same records as shown on the filtered results). 


### Adding and editing individuals

#### Click on "Add a New Person" 
A dialog should pop-up with these fields:

![Add Person](https://user-images.githubusercontent.com/571478/168404638-d871dcb1-c75b-43bc-89ed-e22f2dd20ca8.png)

* Fill out the fields
* Click "Insert"
This should load a page with the information you added along with options to add an address and phone numbers. 
You should be able to
* Edit the user's general information by clicking "Edit"
* Add several phone numbers
* Delete phone numbers

##### Work with a new address
* Click the "Add an address" button
* Enter an address and insert
* Delete the address
* You should get a message saying the address was completely removed from the database since your user was the only one using it. 

##### Work with an existing address
* Find another person to share an address (Type "Julian" and select Julian Loscalzo to add as a housemate)
* Edit the address
* Change the salutations.
* Save
* Delete the address (this should provide a message saying the person was removed from the address but that the address itself was not deleted because it was in use by Julian Loscalzo. 

##### Exporting vCard
* Click on "Export vCard"



