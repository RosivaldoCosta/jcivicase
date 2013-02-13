using System;
using System.Web;
using System.Web.Services;
using System.Web.Services.Protocols;
using System.Data.SqlClient;
using System.Collections.Generic;
using Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities;
using System.Reflection;
using System.Resources;
using System.Data;
using System.Text;
using System.Web.Services.Description;
//using Sante.EMR.SmartClient.Interface.BusinessEntities;

[WebService(Namespace = "http://emr.santegroup.org/")]
[WebServiceBinding(ConformsTo = WsiProfiles.BasicProfile1_1)]
public class DataService : System.Web.Services.WebService
{
    private SqlConnection connection = new SqlConnection("Initial Catalog=emrtest;Data Source=localhost;Integrated Security=SSPI;");

    public DataService()
    {

        //Uncomment the following line if using designed components 
        //InitializeComponent(); 
    }



    [WebMethod]
    public void TestMethod(Intake i)
    {
        if (i == null)
            throw new ArgumentNullException("i", "i is null.");
    }

    private string GenerateSQL(object o, string table)
    {
        StringBuilder sql = new StringBuilder("INSERT INTO " + table);
        sql.Append(" (");
        int columnCnt = 0;
        int valueCnt = 0;

        foreach (PropertyInfo p in o.GetType().GetProperties())
        {

            if (isValid(p.Name))
            {
                sql.Append(p.Name);
                sql.Append(",");
                columnCnt++;
            }



        }

        sql.Remove(sql.ToString().Length - 1, 1);
        sql.Append(") VALUES (");

        foreach (PropertyInfo p in o.GetType().GetProperties())
        {

            if (isValid(p.Name))
            {
                if (p.PropertyType != typeof(int) && p.PropertyType != typeof(long))
                {
                    sql.Append(string.Format("'{0}'", p.GetValue(o, null)));

                }
                else
                    sql.Append(p.GetValue(o, null));

                sql.Append(",");
                valueCnt++;
            }


        }

        if (columnCnt != valueCnt)
        {
            Exception e = new Exception("Column and Value counts do not match");
            e.Data.Add("NumOfColumns", columnCnt);
            e.Data.Add("NumOfValues", valueCnt);
            throw e;
        }

        sql.Remove(sql.ToString().Length - 1, 1);
        sql.Append(")");


        Console.WriteLine(sql.ToString());

        return sql.ToString();

    }

    [WebMethod]
    public bool SaveNextTask(Task n)
    {
        SqlCommand command = new SqlCommand();
        command.Connection = connection;
        command.Connection.Open();
        string caseNumSql = string.Format("SELECT CaseNumber From CaseTable2 WHERE UserGeneratedCaseNumber = {0}", n.Owner);
        command.CommandText = caseNumSql;
        SqlDataReader r = command.ExecuteReader();

        if (r != null)
        {
            if (r.Read())
            {
                int caseNum = Convert.ToInt32(r.GetSqlInt64(1));
                n.Owner = caseNum;

                string sql = GenerateSQL(n, "NextTask");

                sql = sql.Replace("NextTaskName", "NextTask");
                sql = sql.Replace("Owner", "CaseNumber");
                command = new SqlCommand();
                command.CommandText = sql;

                try
                {
                    r.Close();
                    command.ExecuteReader();
                }
                catch (Exception ex)
                {
                    throw;
                }
                finally
                {
                    connection.Close();

                }

                Console.WriteLine(sql);
            }
            return true;
        }

        return false;
    }

    [WebMethod]
    public bool SaveMCTDispatch(MCTDispatch m)
    {
        return true;
    }

    [WebMethod]
    public bool SaveAssessmentAndTreatmentForm(MCTAssessmentAndTreatment a)
    {
        return true;
    }

    [WebMethod]
    public bool SaveAuthorizationToReleaseForm(AuthorizationRelease a)
    {
        return true;
    }

    [WebMethod]
    public bool SaveConsentForServicesForm(ConsentForServices c)
    {
        return true;
    }

    [WebMethod]
    public bool SaveHumanRightsNotificationForm(HumanRights h)
    {
        return true;
    }


    [WebMethod]
    public bool SaveIndividualTreatmentPlanForm(IHITIndividualTreatment i)
    {
        return true;
    }

    [WebMethod]
    public bool SaveInformedConsentForm(InformedConsent c)
    {
        return true;
    }


    [WebMethod]
    public bool SaveMedicalEvaluationForm(medeval m)
    {
        return true;
    }

    [WebMethod]
    public bool SaveNotes(ProgressNote n)
    {
        return true;
    }

    [WebMethod]
    public bool SaveTelephoneContact(Telephone t)
    {
        return true;
    }

    [WebMethod]
    public bool SaveLethalityForm(Lethality form)
    {
        return true;
    }

    [WebMethod]
    public bool SavePrivacyPractices(PrivacyPractices form)
    {
        return true;
    }

    [WebMethod]
    public bool SavePatient(Patient p)
    {
        SqlCommand command = new SqlCommand();
        command.Connection = connection;

        string caseNumSql = string.Format("SELECT CaseNumber From CaseTable2 WHERE UserGeneratedCaseNumber = {0}", p.CaseNumber);
        command.CommandText = caseNumSql;
        SqlDataReader r = command.ExecuteReader();

        if (r != null)
        {
            if (r.Read())
            {
                int caseNum = Convert.ToInt32(r.GetSqlInt64(1));
                p.CaseNumber = caseNum;

                string sql = GenerateSQL(p, "Patient");
                command.CommandText = sql;

                try
                {
                    command.ExecuteReader();
                }
                catch (Exception ex)
                {
                    throw;
                }
                finally
                {
                    connection.Close();

                }

            }
        }

        //Console.WriteLine(sql.ToString());

        return true;
    }

    [WebMethod]
    public bool SaveCase(Case c)
    {
        SqlCommand command = new SqlCommand();
        command.Connection = connection;
        //command.Connection.Open();
        string sql = GenerateSQL(c, "Casetable2");
        command.CommandText = sql;

        try
        {
            //command.ExecuteReader();
        }
        catch (Exception ex)
        {
            throw;
        }
        finally
        {
            connection.Close();

        }

        Console.WriteLine(sql.ToString());

        return true;

    }


    [WebMethod]
    public bool SaveForm(Form form)
    {
        SqlCommand command = new SqlCommand();
        command.Connection = connection;
        //command.Connection.Open();
        
        int existingCaseNumber = GetExistingCaseNumber("UserGeneratedCaseNumber",form.CaseNumber);

        if (form.CaseNumber > 0)
        {
            form.CaseNumber = existingCaseNumber;
            string tableName = form.GetType().Name;

            string sql = GenerateSQL(form, tableName);

            command = new SqlCommand();
            command.CommandText = sql;
            command.Connection = connection;

            try
            {
                command.ExecuteReader();
            }
            catch (Exception ex)
            {
                //ex.Data.Add("SQL",sql);
                throw ex;
            }
            finally
            {
                connection.Close();

            }
        }
        //Console.WriteLine(sql.ToString());

        return true;
    }

    private int GetExistingCaseNumber(string field, int casenumber)
    {
        string caseNumSql = string.Format("SELECT CaseNumber From CaseTable2 WHERE {0} = {1}", casenumber, field);
        SqlCommand command = new SqlCommand();
        command.CommandText = caseNumSql;
        command.Connection = connection;
        SqlDataReader r = command.ExecuteReader();
        int existingCaseNum = 0;

        if (r != null)
        {
            if (r.Read())
            {

                existingCaseNum = Convert.ToInt32(r.GetInt64(0));
                return existingCaseNum;
            }
        }
        else
        {
            return GetExistingCaseNumber("CaseNumber", casenumber);
        }

        return existingCaseNum;
    }



    /// <summary>
    /// 
    /// </summary>
    /// <param name="p"></param>
    /// <returns></returns>
    private bool isValid(string p)
    {
        string[] invalidvalues = new string[] { "Patient", "FormName", "Forms", "NextTaskObj", "PhoneContacts", "NextTaskDate", "CaseNumber" };

        foreach (string s in invalidvalues)
        {
            if (p.Equals(s))
                return false;
        }

        return true;
    }

    [WebMethod]
    public string[] GetModifiedTables()
    {
        SqlCommand command = new SqlCommand();
        command.Connection = connection;
        command.CommandText = "select TableName,LastModifiedDate from TableModifiedReport";
        command.ExecuteReader();
        string[] tablenames = new string[9];

        //SqlDataReader reader = new SqlDataReader();
        //if (reader.HasRows)
        //{
        //   // tablenames = new string[reader.r]    
        //    while (reader.NextResult)
        //    {

        //    }
        //}

        return tablenames;
    }

    [WebMethod]
    public List<Patient> Search(string firstname, string lastname, string phone, string address, string casenumber)
    {
        List<Patient> patients = new List<Patient>();
        SqlCommand command = new SqlCommand("Search", connection);
        command.CommandType = System.Data.CommandType.StoredProcedure;

        SqlParameter myParm = command.Parameters.Add("@firstname", SqlDbType.NVarChar, 15);
        if (firstname != null) myParm.Value = firstname;
        else myParm.Value = null;

        myParm = command.Parameters.Add("@lastname", SqlDbType.NVarChar, 15);
        if (lastname != null) myParm.Value = lastname;
        else myParm.Value = null;

        myParm = command.Parameters.Add("@phone", SqlDbType.NVarChar, 15);
        if (phone != null) myParm.Value = phone;
        else myParm.Value = null;

        myParm = command.Parameters.Add("@address", SqlDbType.NVarChar, 15);
        if (address != null) myParm.Value = address;
        else myParm.Value = null;

        myParm = command.Parameters.Add("@casenumber", SqlDbType.NVarChar, 15);
        if (casenumber != null) myParm.Value = casenumber;
        else myParm.Value = null;

        command.Connection.Open();
        SqlDataReader reader = command.ExecuteReader();

        //CaseNumber,PFirstname, PLastName, StreetAddress, Phone, SSN, DOB

        if (reader.HasRows)
        {
            while (reader.Read())
            {
                Patient p = new Patient();
                p.CaseNumber = Convert.ToInt32(reader.GetInt64(0));
                //p.FirstName = reader.GetString(1);
                //p.LastName = reader.GetString(2);
                //p.StreeAddress = reader.GetString(3);
                //p.Phone = reader.GetString(4);
                p.SSN = reader.GetString(5);
                p.DOB = reader.GetDateTime(6);

                patients.Add(p);
            }
        }

        reader.Close();
        connection.Close();
        return patients;



    }

    [WebMethod]
    public List<Task> GetTasks(int casenumber)
    {
        List<Task> tasks = new List<Task>();
        string sql = string.Format("select SetDate,NextTask,Comments,DateDue,Initial,DateCompleted,AppointmentStatus from NextTask where CaseNumber={0}", casenumber);//resources.GetString("sqlValue");
        SqlCommand command = new SqlCommand();
        command.Connection = connection;
        command.Connection.Open();
        command.CommandText = sql; // "select TableName,LastModifiedDate from TableModifiedReport";
        SqlDataReader reader = command.ExecuteReader();

        if (reader.HasRows)
        {
            while (reader.Read())
            {
                Task n = new Task();

                n.SetDate = reader.GetDateTime(0);//Convert.ToInt32(reader.GetInt64(0));
                // n.Name = reader.GetString(1);
                n.Comments = reader.GetString(2);
                n.DateDue = reader.GetDateTime(3);
                n.Initial = reader.GetString(4);
                n.DateCompleted = reader.GetDateTime(5);
                //n.Appt = reader.GetString(6);

                tasks.Add(n);
            }
        }

        connection.Close();
        reader.Close();
        return tasks;

    }

    [WebMethod]
    public List<Case> GetOPSCases()
    {
        ResourceManager resources = new ResourceManager(@"C:\Documents and Settings\Charles\My Documents\sante\code\EMR SmartClient\Web Services\.Web Services.Resources", Assembly.GetExecutingAssembly());
        List<Case> cases = new List<Case>();
        string sql = resources.GetString("sqlValue");
        SqlConnection connection = new SqlConnection(resources.GetString("connectionValue"));
        SqlCommand command = new SqlCommand();
        command.Connection = connection;
        command.Connection.Open();
        command.CommandText = sql; // "select TableName,LastModifiedDate from TableModifiedReport";
        SqlDataReader reader = command.ExecuteReader();

        //n.CaseNumber,n.NextTask,n.SetDate,n.DateDue, DateCompleted

        if (reader.HasRows)
        {
            while (reader.Read())
            {
                Case c = new Case();

                c.CaseNumber = Convert.ToInt32(reader.GetInt64(0));
                c.NextTask = reader.GetString(1);
                c.SetDate = reader.GetDateTime(2);
                c.Status = Case.SetStatus(reader.GetString(5));
                //c.NextTaskObj = new NextTask();

                c.NextTaskDate = reader.GetDateTime(6);

                cases.Add(c);
            }
        }

        reader.Close();
        connection.Close();
        return cases;

    }

    [WebMethod]
    public bool RetrieveMCTCases()
    {
        return true;
    }

    [WebMethod]
    public bool RetrieveIHITCases()
    {
        return true;
    }

}
